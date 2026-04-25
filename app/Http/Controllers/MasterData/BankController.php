<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $banks = Bank::when($search, function ($query, $search) {
            // 🚨 ปรับปรุง 1: หุ้ม orWhere ไว้ใน Group ป้องกัน SQL Scope ทะลุหากมีการใส่ where() เพิ่มในอนาคต
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // 🚨 ปรับปรุง 2: ใส่เพื่อให้เวลาผู้ใช้กดเปลี่ยนหน้า (Page 2) ค่าที่ Search ไว้จะไม่หายไป

        // 🚨 ปรับปรุง 3: ใช้ฟังก์ชัน compact() ทำให้โค้ดสั้นและอ่านง่ายขึ้น
        return view('master-data.bank.index', compact('banks', 'search'));
    }

    public function create()
    {
        return view('master-data.bank.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:banks,code',
            'account_name' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:50',
        ]);

        // จัดการค่า is_active (ถ้าไม่ติ๊ก Checkbox ค่าจะเป็น false อัตโนมัติ)
        $validated['is_active'] = $request->boolean('is_active');

        // 🚨 ปรับปรุง 4: นำ executeSafely มาครอบ เพื่อทำ DB Transaction และเก็บ Log อัตโนมัติ
        return $this->executeSafely(function () use ($validated) {
            Bank::create($validated);
        }, 'เพิ่มธนาคารเรียบร้อยแล้ว');
    }

    // 🚨 ปรับปรุง 5: ใช้ Route Model Binding (รับค่าเป็น Type `Bank` เลย ไม่ต้องหา findOrFail เอง)
    public function edit(Bank $bank)
    {
        return view('master-data.bank.edit', compact('bank'));
    }

    public function update(Request $request, Bank $bank)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // ยกเว้น ID ของตัวเองในการเช็ก unique
            'code' => 'required|string|max:50|unique:banks,code,' . $bank->id,
            'account_name' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:50',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $this->executeSafely(function () use ($bank, $validated) {
            $bank->update($validated);
        }, 'อัปเดตธนาคารเรียบร้อยแล้ว');
    }

    public function destroy(Bank $bank)
    {
        return $this->executeSafely(function () use ($bank) {
            // 🚨 ปรับปรุง 6: สำหรับ ERP/POS Master Data แนะนำให้ใช้ Soft Delete เพื่อไม่ให้บิลเก่าๆ พัง
            $bank->update(['is_active' => false]);

            // หมายเหตุ: ถ้าต้องการลบถาวรจริงๆ ให้แก้เป็น $bank->delete();
        }, 'ระงับการใช้งานธนาคารเรียบร้อยแล้ว');
    }
}
