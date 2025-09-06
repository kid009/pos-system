<?php

namespace App\Http\Controllers\Store;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    public function adjustmentCreate()
    {
        // ดึงสินค้าของร้านตัวเองมาให้เลือก
        $products = Product::where('tenant_id', auth()->user()->tenant_id)->orderBy('name')->get();

        return view('store.stock.adjustment-form', [
            'products' => $products
        ]);
    }

    public function adjustmentStore(Request $request)
    {
        // เพิ่มส่วน Validation เข้ามา
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:add,remove',
            'quantity' => 'required|integer|min:1',
            'notes' => 'required|string|max:255',
        ]);
        
        dd($request->all()); // Dump and die for now
    }
}
