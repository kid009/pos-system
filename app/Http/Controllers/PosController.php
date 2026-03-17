<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Customer;
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
        $shops = $user->role === 'admin'
            ? Shop::where('is_active', true)->get()
            : Shop::where('id', $user->shop_id ?? 1)->get();

        // 2. ดึงข้อมูลหมวดหมู่ทั้งหมด
        $categories = Category::where('is_active', true)->get();

        // 3. ดึงข้อมูลลูกค้า
        $customers = Customer::where('is_active', true)->get();

        // 4. ดึงข้อมูลสินค้า พร้อม Mapping ให้เป็น Array ที่เบาที่สุดสำหรับหน้าจอ POS
        $products = Product::where('is_active', true)->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'shop_id' => $product->shop_id,
                'category_id' => $product->category_id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => (float) $product->price,
                'stock' => (float) $product->stock,
                'unit' => $product->unit,
                'image' => $product->image_path ? asset('images/' . $product->image_path) : asset('images/no_pic.png'),
            ];
        });

        return view('pos.index', [
            'shops' => $shops,
            'categories' => $categories,
            'products' => $products,
            'customers' => $customers,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'customer_id' => 'nullable|exists:customers,id',
            'cart' => 'required|array|min:1',
            'receive_amount' => 'required|numeric|min:0',
            'transaction_date' => 'nullable|date',
        ]);

        try {
            $transaction = DB::transaction(function () use ($request) {
                $date = $request->transaction_date ? \Carbon\Carbon::parse($request->transaction_date) : now();
                $datePrefix = 'REC' . $date->format('Ymd');

                $lastTx = Transaction::where('invoice_no', 'like', $datePrefix . '%')
                    ->lockForUpdate()
                    ->orderBy('invoice_no', 'desc')
                    ->first();

                $queue = 1;
                if ($lastTx) {
                    $lastQueue = (int) substr($lastTx->invoice_no, -4);
                    $queue = $lastQueue + 1;
                }

                $invoiceNo = $datePrefix . str_pad($queue, 4, '0', STR_PAD_LEFT);

                // สร้างหัวบิล
                $tx = Transaction::create([
                    'invoice_no' => $invoiceNo,
                    'transaction_date' => $date->format('Y-m-d'),
                    'shop_id' => $request->shop_id,
                    'customer_id' => $request->customer_id,
                    'user_id' => Auth::user()->id,
                    'total_amount' => 0,
                    'receive_amount' => $request->receive_amount,
                    'change_amount' => 0,
                    'created_at' => $date,
                ]);

                $calculatedTotal = 0;

                foreach ($request->cart as $item) {
                    $product = Product::find($item['id']);
                    if (!$product) throw new Exception("ไม่พบสินค้า " . $item['name']);

                    // 💡 หักสต็อกสินค้า
                    $product->decrement('stock', $item['qty']);

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
                'invoice_no' => $transaction->invoice_no
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }
}
