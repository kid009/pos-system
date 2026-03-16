<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. ดึงข้อมูลร้านค้า (Admin เห็นทุกร้าน, Staff เห็นแค่ร้านที่ตัวเองสังกัด)
        // เพื่อความง่ายในตอนนี้ หาก Staff ไม่มี shop_id เราจะให้เห็นสาขาแรกไปก่อน
        $shops = $user->role === 'admin'
            ? Shop::where('is_active', true)->get()
            : Shop::where('id', $user->shop_id ?? 1)->get();

        // 2. ดึงข้อมูลหมวดหมู่ทั้งหมด
        $categories = Category::where('is_active', true)->get();

        // 3. ดึงข้อมูลสินค้า พร้อม Mapping ให้เป็น Array ที่เบาที่สุดสำหรับหน้าจอ POS
        // 💡 Architect Tip: เราไม่ส่ง Model ไปทั้งก้อน เพราะมันมีข้อมูลที่ไม่จำเป็นเยอะ (เปลือง Memory)
        $products = Product::where('is_active', true)->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'shop_id' => $product->shop_id,
                'category_id' => $product->category_id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => (float) $product->price, // ต้องแปลงเป็น Float ให้ JS คำนวณได้
                'unit' => $product->unit,
                'image' => $product->image_path ? asset('storage/' . $product->image_path) : asset('images/no_pic.png'),
            ];
        });

        return view('pos.index', [
            'shops' => $shops,
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'cart' => 'required|array|min:1',
            'receive_amount' => 'required|numeric|min:0',
        ]);

        try {
            $transaction = DB::transaction(function () use ($request) {

                // 💡 1. สร้าง Format เลขบิลของวันนี้ (เช่น INV20260315)
                $todayPrefix = 'INV' . now()->format('Ymd');

                // 💡 2. หาเลขบิลล่าสุดของวันนี้ เพื่อเอามาบวก 1
                // ใช้ lockForUpdate() เพื่อป้องกันพนักงาน 2 คนกดชำระเงินพร้อมกันแล้วได้เลขบิลซ้ำ
                $lastTx = Transaction::where('invoice_no', 'like', $todayPrefix . '%')
                    ->lockForUpdate()
                    ->orderBy('invoice_no', 'desc')
                    ->first();

                $queue = 1;
                if ($lastTx) {
                    // ตัดเอาเฉพาะ 4 ตัวท้ายมาแปลงเป็นตัวเลข แล้วบวก 1
                    $lastQueue = (int) substr($lastTx->invoice_no, -4);
                    $queue = $lastQueue + 1;
                }

                // 💡 3. ประกอบร่างเลขบิล (เติมเลข 0 ด้านหน้าให้ครบ 4 หลัก)
                // ผลลัพธ์: INV202603150001
                $invoiceNo = $todayPrefix . str_pad($queue, 4, '0', STR_PAD_LEFT);

                // สร้างหัวบิล
                $tx = Transaction::create([
                    'invoice_no' => $invoiceNo, // 🚨 ใส่เลขบิลตรงนี้
                    'shop_id' => $request->shop_id,
                    'user_id' => Auth::user()->id,
                    'total_amount' => 0,
                    'receive_amount' => $request->receive_amount,
                    'change_amount' => 0,
                ]);

                $calculatedTotal = 0;

                foreach ($request->cart as $item) {
                    $product = Product::find($item['id']);
                    if (!$product) throw new Exception("ไม่พบสินค้า " . $item['name']);

                    $subtotal = $product->price * $item['qty'];
                    $calculatedTotal += $subtotal;

                    $tx->details()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'cost' => $product->cost,
                        'price' => $product->price,
                        'qty' => $item['qty'],
                        'subtotal' => $subtotal
                    ]);
                }

                $tx->update([
                    'total_amount' => $calculatedTotal,
                    'change_amount' => max(0, $request->receive_amount - $calculatedTotal)
                ]);

                return $tx;
            });

            return response()->json([
                'success' => true,
                'message' => 'บันทึกบิลสำเร็จ',
                'invoice_no' => $transaction->invoice_no // 🚨 ส่งเลขบิลกลับไปให้หน้าจอ POS นำไปปริ้น
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
}
