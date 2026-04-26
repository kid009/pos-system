<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('master-data.product.index', compact('products', 'search'));
    }

    public function create()
    {
        $categories = ProductCategory::where('is_active', true)->get();
        return view('master-data.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:product_categories,id',
            'sku' => 'required|string|max:50|unique:products,sku',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock_qty' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'image' => 'nullable|string',
            'affiliate_link' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $this->executeSafely(function () use ($validated) {
            Product::create($validated);
        }, 'เพิ่มสินค้าเรียบร้อยแล้ว');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::where('is_active', true)->get();
        return view('master-data.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:product_categories,id',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock_qty' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'image' => 'nullable|string',
            'affiliate_link' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $this->executeSafely(function () use ($product, $validated) {
            $product->update($validated);
        }, 'อัปเดตสินค้าเรียบร้อยแล้ว');
    }

    public function destroy(Product $product)
    {
        return $this->executeSafely(function () use ($product) {
            $product->update(['is_active' => false]);
        }, 'ระงับการใช้งานสินค้าเรียบร้อยแล้ว');
    }
}