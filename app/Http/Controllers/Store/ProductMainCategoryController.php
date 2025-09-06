<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\ProductMainCategory;
use Illuminate\Http\Request;

class ProductMainCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mainCategories = ProductMainCategory::where('tenant_id', auth()->user()->tenant_id)
            ->latest()->paginate(10);

        return view('store.product-main-categories.index', [
            'mainCategories' => $mainCategories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('store.product-main-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['created_by'] = auth()->id();

        ProductMainCategory::create($validated);

        return redirect()->route('store.product-main-categories.index')->with('success', 'Main category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductMainCategory $productMainCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $productMainCategory = ProductMainCategory::find($id);

        return view('store.product-main-categories.edit', [
            'productMainCategory' => $productMainCategory
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $productMainCategory = ProductMainCategory::find($id);

        $validated = $request->validate(['name' => 'required|string|max:255']);

        $validated['updated_by'] = auth()->id();

        $productMainCategory->update($validated);
        
        return to_route('store.product-main-categories.index')->with('success', 'Main category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $productMainCategory = ProductMainCategory::find($id);
        $productMainCategory->delete();
        return back()->with('success', 'Main category deleted successfully.');
    }
}
