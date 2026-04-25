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

        $shippingMethods = ShippingMethod::when($search, function ($query, $search) {
            // ถึงแม้ตอนนี้จะมีแค่เงื่อนไขเดียว แต่การสร้าง Closure $query->where() เผื่อไว้
            // จะช่วยให้ปลอดภัยหากมีการเพิ่ม orWhere ในอนาคตครับ
            $query->where('name', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // 🚨 ป้องกันบั๊กค่า Search หายเวลาผู้ใช้กดเปลี่ยนหน้าเพจ

        // ใช้ compact() เพื่อความสะอาดของโค้ด
        return view('master-data.shipping-method.index', compact('shippingMethods', 'search'));
    }

    public function create()
    {
        return view('master-data.shipping-method.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        // 🚨 นำ executeSafely มาครอบ เพื่อดักจับ Error และทำ DB Transaction
        return $this->executeSafely(function () use ($validated) {
            ShippingMethod::create($validated);
        }, 'เพิ่มบริษัทขนส่งเรียบร้อยแล้ว', 'shipping-method.index');
    }

    // 🚨 ใช้ Route Model Binding เพื่อลดการเขียน findOrFail
    public function edit(ShippingMethod $shippingMethod)
    {
        return view('master-data.shipping-method.edit', compact('shippingMethod'));
    }

    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $this->executeSafely(function () use ($shippingMethod, $validated) {
            $shippingMethod->update($validated);
        }, 'อัปเดตบริษัทขนส่งเรียบร้อยแล้ว', 'shipping-method.index');
    }

    public function destroy(ShippingMethod $shippingMethod)
    {
        return $this->executeSafely(function () use ($shippingMethod) {
            // 🚨 เปลี่ยนจากการลบถาวร (delete) เป็น Soft Delete (is_active = false)
            // เพื่อป้องกันปัญหาประวัติการขาย (บิลเก่าๆ) ที่อ้างอิงขนส่งเจ้านี้เกิด Error หาข้อมูลไม่เจอ
            $shippingMethod->update(['is_active' => false]);
        }, 'ระงับการใช้งานบริษัทขนส่งเรียบร้อยแล้ว');
    }
}
