<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shops = Shop::latest()->paginate(10);
        return view('shop.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shop.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'branch_code' => 'nullable|string|max:50|unique:shops,branch_code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'tax_id' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        // จัดการ Boolean checkbox (หากไม่ติ๊กจะเป็น false)
        $validated['is_active'] = $request->boolean('is_active');

        Shop::create($validated);

        return redirect()->route('shop.index')->with('success', 'Shop created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shop = Shop::findOrFail($id);
        return view('shop.show', compact('shop'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shop = Shop::findOrFail($id);
        return view('shop.edit', compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shop = Shop::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'branch_code' => 'nullable|string|max:50|unique:shops,branch_code,' . $shop->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'tax_id' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $shop->update($validated);

        return redirect()->route('shop.index')->with('success', 'Shop updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shop = Shop::findOrFail($id);
        $shop->delete();

        return redirect()->route('shop.index')->with('success', 'Shop deleted successfully.');
    }
}
