<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $suppliers = Supplier::when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('master-data.supplier.index', compact('suppliers', 'search'));
    }

    public function create()
    {
        return view('master-data.supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:50',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $this->executeSafely(function () use ($validated) {
            Supplier::create($validated);
        }, 'เพิ่มซัพพลายเออร์เรียบร้อยแล้ว', 'suppliers.index');
    }

    public function edit(Supplier $supplier)
    {
        return view('master-data.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:50',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $this->executeSafely(function () use ($supplier, $validated) {
            $supplier->update($validated);
        }, 'อัปเดตซัพพลายเออร์เรียบร้อยแล้ว', 'suppliers.index');
    }

    public function destroy(Supplier $supplier)
    {
        return $this->executeSafely(function () use ($supplier) {
            $supplier->update(['is_active' => false]);
        }, 'ระงับการใช้งานซัพพลายเออร์เรียบร้อยแล้ว');
    }
}
