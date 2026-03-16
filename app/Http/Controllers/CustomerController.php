<?php

namespace App\Http\Controllers;

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
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers', 'search'));
    }

    public function create()
    {
        $shops = Auth::user()->role === 'admin'
            ? Shop::where('is_active', true)->get()
            : Shop::where('id', Auth::user()->shop_id)->get();

        return view('admin.customers.create', compact('shops'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'เพิ่มข้อมูลลูกค้าสำเร็จ');
    }

    public function edit(Customer $customer)
    {
        $shops = Auth::user()->role === 'admin'
            ? Shop::where('is_active', true)->get()
            : Shop::where('id', Auth::user()->shop_id)->get();

        return view('admin.customers.edit', compact('customer', 'shops'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'อัปเดตข้อมูลลูกค้าสำเร็จ');
    }

    public function destroy(Customer $customer)
    {
        $customer->update(['is_active' => false]); // ใช้ Soft Delete หรือเปลี่ยนสถานะแทนการลบทิ้งจริง
        return redirect()->route('customers.index')->with('success', 'ยกเลิกข้อมูลลูกค้าสำเร็จ');
    }
}
