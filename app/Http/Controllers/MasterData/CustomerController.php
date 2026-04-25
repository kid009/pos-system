<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $query = Customer::with('shop');

        // จัดการสิทธิ์ Admin/Staff
        if ($user->role !== 'admin') {
            $query->where('shop_id', $user->shop_id ?? 1);
        }

        // ค้นหาจากชื่อ หรือ เบอร์โทร
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()
            ->paginate(15)
            ->withQueryString();

        return view('master-data.customer.index', compact('customers', 'search'));
    }

    public function create()
    {
        $shops = Auth::user()->role === 'admin'
            ? Shop::where('is_active', true)->get()
            : Shop::where('id', Auth::user()->shop_id)->get();

        return view('master-data.customer.create', compact('shops'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $this->executeSafely(function () use ($validated) {
            Customer::create($validated);
        }, 'เพิ่มข้อมูลลูกค้าสำเร็จ', 'customer.index');
    }

    public function edit(Customer $customer)
    {
        $shops = Auth::user()->role === 'admin'
            ? Shop::where('is_active', true)->get()
            : Shop::where('id', Auth::user()->shop_id)->get();

        return view('master-data.customer.edit', compact('customer', 'shops'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $this->executeSafely(function () use ($customer, $validated) {
            $customer->update($validated);
        }, 'อัปเดตข้อมูลลูกค้าสำเร็จ', 'customer.index');
    }

    public function destroy(Customer $customer)
    {
        return $this->executeSafely(function () use ($customer) {
            $customer->update(['is_active' => false]);
        }, 'ยกเลิกข้อมูลลูกค้าสำเร็จ');
    }
}
