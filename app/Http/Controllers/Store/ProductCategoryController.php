<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\ProductMainCategory;
use App\Http\Controllers\Controller;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ProductCategory::with('productMainCategory') // Eager load
            ->where('tenant_id', auth()->user()->tenant_id)
            ->latest()->paginate(10);

        return view('store.product-categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ดึงเฉพาะหมวดหมู่หลักของร้านตัวเองมาให้เลือก
        $mainCategories = ProductMainCategory::where('tenant_id', auth()->user()->tenant_id)->get();
        
        return view('store.product-categories.create', [
            'mainCategories' => $mainCategories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_main_category_id' => 'required|exists:product_main_categories,id',
        ]);
        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['created_by'] = auth()->id();

        ProductCategory::create($validated);

        return redirect()->route('store.product-categories.index')->with('success', 'Sub category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $productCategory = ProductCategory::find($id);
        $mainCategories = ProductMainCategory::where('tenant_id', auth()->user()->tenant_id)->get();

        return view('store.product-categories.edit', [
            'productCategory' => $productCategory,
            'mainCategories' => $mainCategories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $productCategory = ProductCategory::find($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_main_category_id' => 'required|exists:product_main_categories,id',
        ]);

        $validated['updated_by'] = auth()->id();

        $productCategory->update($validated);

        return redirect()->route('store.product-categories.index')->with('success', 'Sub category created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $productCategory = ProductCategory::find($id);
        $productCategory->delete();
        return back()->with('success', 'Main category deleted successfully.');
    }
}
