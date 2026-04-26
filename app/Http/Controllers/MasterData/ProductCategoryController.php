<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $productCategories = ProductCategory::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('master-data.product-category.index', compact('productCategories', 'search'));
    }

    public function create()
    {
        return view('master-data.product-category.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $this->executeSafely(function () use ($validated) {
            ProductCategory::create($validated);
        }, 'เพิ่มหมวดหมู่สินค้าเรียบร้อยแล้ว');
    }

    public function edit(ProductCategory $productCategory)
    {
        return view('master-data.product-category.edit', compact('productCategory'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $this->executeSafely(function () use ($productCategory, $validated) {
            $productCategory->update($validated);
        }, 'อัปเดตหมวดหมู่สินค้าเรียบร้อยแล้ว');
    }

    public function destroy(ProductCategory $productCategory)
    {
        return $this->executeSafely(function () use ($productCategory) {
            $productCategory->update(['is_active' => false]);
        }, 'ระงับการใช้งานหมวดหมู่สินค้าเรียบร้อยแล้ว');
    }
}