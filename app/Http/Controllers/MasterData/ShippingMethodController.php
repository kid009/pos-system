<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $shippingMethods = ShippingMethod::when($search, function ($q, $search) {
                return $q->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('master-data.shipping-method.index', [
            'shippingMethods' => $shippingMethods,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('master-data.shipping-method.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ShippingMethod::create([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('shipping-methods.index')->with('success', 'เพิ่มบริษัทขนส่งเรียบร้อยแล้ว');
    }

    public function edit(string $id)
    {
        $shippingMethod = ShippingMethod::findOrFail($id);

        return view('master-data.shipping-method.edit', [
            'shippingMethod' => $shippingMethod,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $shippingMethod = ShippingMethod::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $shippingMethod->update([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('shipping-methods.index')->with('success', 'อัปเดตบริษัทขนส่งเรียบร้อยแล้ว');
    }

    public function destroy(string $id)
    {
        $shippingMethod = ShippingMethod::findOrFail($id);
        $shippingMethod->delete();

        return redirect()->route('shipping-methods.index')->with('success', 'ลบบริษัทขนส่งเรียบร้อยแล้ว');
    }
}