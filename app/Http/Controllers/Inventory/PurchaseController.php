<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $supplierId = $request->input('supplier_id');

        // ดึงข้อมูลบิลรับเข้า พร้อมชื่อซัพพลายเออร์
        $purchases = Purchase::with('supplier')
            ->when($search, function ($query, $search) {
                $query->where('doc_no', 'like', "%{$search}%");
            })
            ->when($supplierId, function ($query, $supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $suppliers = Supplier::where('is_active', true)->get();

        return view('inventory.purchase.index', compact('purchases', 'search', 'supplierId', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        return view('inventory.purchase.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'doc_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            // net_amount ควรให้ระบบคำนวณเองเพื่อป้องกัน User กรอกตัวเลขขัดแย้งกัน
        ]);

        // คำนวณยอดสุทธิ: (ราคาสินค้า + ค่าขนส่ง) - ส่วนลด
        $validated['net_amount'] = ($validated['total_amount'] + $validated['shipping_cost']) - $validated['discount'];

        // สร้างเลขที่เอกสารอัตโนมัติ (เช่น IN-202604-0001)
        $validated['doc_no'] = $this->generateDocNo();

        return $this->executeSafely(function () use ($validated) {
            // 🚨 ในโลกความเป็นจริง เราจะดึงข้อมูล Array ของรายการสินค้ามาเซฟลง PurchaseDetail พร้อมกันที่นี่ครับ
            Purchase::create($validated);
        }, 'บันทึกเอกสารรับเข้าเรียบร้อยแล้ว', 'purchases.index');
    }

    public function destroy(Purchase $purchase)
    {
        return $this->executeSafely(function () use ($purchase) {
            // กรณียกเลิกบิลรับเข้า เราจะเปลี่ยนสถานะแทนการลบ เพื่อเก็บประวัติไว้ว่าเคยมีการยกเลิก
            $purchase->update(['status' => 'cancelled']);

            // 🚨 อนาคต: ถ้ายกเลิกบิลนี้ ต้องไปสั่งลด stock_qty ในตาราง products ด้วย
        }, 'ยกเลิกเอกสารรับเข้าเรียบร้อยแล้ว');
    }

    /**
     * ฟังก์ชันสำหรับสร้างเลขที่เอกสารรันนิ่ง (Running Number)
     */
    private function generateDocNo()
    {
        $prefix = 'IN-';
        $yearMonth = Carbon::now()->format('Ym'); // ผลลัพธ์: 202604

        // หาบิลล่าสุดของเดือนนี้
        $lastPurchase = Purchase::where('doc_no', 'like', "{$prefix}{$yearMonth}-%")
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastPurchase) {
            $runningNumber = '0001';
        } else {
            // ตัดเอาเฉพาะตัวเลข 4 หลักท้ายมาบวก 1
            $lastNumber = (int) substr($lastPurchase->doc_no, -4);
            $runningNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        }

        return "{$prefix}{$yearMonth}-{$runningNumber}";
    }
}
