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
    protected $transactionService;

    public function __construct(\App\Services\TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

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
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'customer_id' => 'nullable|exists:customers,id',
            'cart' => 'required|array|min:1',
            'receive_amount' => 'required|numeric|min:0',
            'transaction_date' => 'nullable|date',
            'payment_method' => 'required|in:cash,transfer,credit',
            'discount_amount' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
        ]);

        try {
            $transaction = $this->transactionService->checkout($validated);

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
