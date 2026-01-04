<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\StockIn;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StockInDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockInComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // === กล่องเก็บข้อมูลฟอร์ม (Form Data) ===
    public $import_date;       // วันที่
    public $supplier_name;     // ชื่อร้านค้า
    public $payment_type = 'cash'; // วิธีจ่ายเงิน (ค่าเริ่มต้นเป็นเงินสด)

    // === กล่องเก็บตะกร้าสินค้า (Cart) ===
    public $search_product = ''; // คำที่พิมพ์ค้นหา
    public $items = [];          // อาร์เรย์เก็บรายการสินค้าที่เลือกมา

    public $editingId = null;

    public function mount()
    {
        $this->import_date = Carbon::today()->format('Y-m-d');
    }

    public function openModal()
    {
        $this->reset(['supplier_name', 'items', 'search_product', 'editingId']); // ล้างข้อมูลเก่าทิ้งให้หมด
        $this->payment_type = 'cash';
        $this->import_date = Carbon::today()->format('Y-m-d');

        $this->dispatch('show-stock-modal');
    }

    public function closeModal()
    {
        $this->dispatch('close-stock-modal');
    }

    public function addItem($productId)
    {
        // 1. หาข้อมูลสินค้าจริง
        $product = Product::find($productId);

        // 2. ป้องกันสินค้าซ้ำ (ถ้ามีอยู่แล้วในตะกร้า ไม่ต้องเพิ่ม)
        foreach ($this->items as $item) {
            if ($item['product_id'] == $product->id) {
                return;
            }
        }

        // 3. เพิ่มลง Array $items
        $this->items[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'qty' => 1,                // เริ่มที่ 1 ชิ้น
            'unit_cost' => $product->cost, // ดึงทุนเดิมมาใส่ให้
        ];

        // 4. ล้างคำค้นหา (Dropdown จะได้หุบลง)
        $this->search_product = '';
    }

    // === ฟังก์ชันลบสินค้า ===
    public function removeItem($index)
    {
        unset($this->items[$index]); // ลบตัวที่เลือก
        $this->items = array_values($this->items); // เรียง index ใหม่ (0,1,2..) สำคัญมาก!
    }

    public function edit($id)
    {
        $this->reset(['items', 'search_product']); // ล้างของเก่าก่อน

        // 1. ดึงข้อมูลบิลเก่า พร้อมรายการสินค้า
        $stockIn = StockIn::with('details.product')->find($id);

        // 2. เอาข้อมูลมาใส่ตัวแปร
        $this->editingId = $stockIn->id;
        $this->import_date = $stockIn->import_date;
        $this->supplier_name = $stockIn->supplier_name;
        $this->payment_type = $stockIn->payment_type;

        // 3. แปลงรายการสินค้าเดิม ใส่ลงใน Array $items
        foreach($stockIn->details as $detail) {
            $this->items[] = [
                'product_id' => $detail->product_id,
                'name' => $detail->product->name, // หรือ $detail->product_name ถ้าเก็บ snapshot
                'qty' => $detail->qty,
                'unit_cost' => $detail->unit_cost,
            ];
        }

        // 4. เปิด Modal
        $this->dispatch('show-stock-modal');
    }

    // === ฟังก์ชันบันทึก (พระเอกตัวจริง) ===
    public function save()
    {
        $this->validate([
            'supplier_name' => 'required',
            'items' => 'required|array|min:1',
            'items.*.qty' => 'required|numeric|min:1', // เพิ่ม validate ย่อย
        ]);

        DB::transaction(function () {

            // กรณี: แก้ไข (Update)
            if ($this->editingId) {
                // 1. หาบิลเดิม
                $stockIn = StockIn::find($this->editingId);

                // 2. *** คืนสต็อกเก่ากลับไปก่อน (Revert Stock) ***
                // ดึงรายการเดิมมา แล้ววนลบออกจาก Stock จริง
                $oldDetails = StockInDetail::where('stock_in_id', $this->editingId)->get();

                foreach ($oldDetails as $oldDetail) {
                    $prod = Product::find($oldDetail->product_id);

                    if ($prod) {
                        $prod->decrement('stock_qty', $oldDetail->qty); // ลบออก
                    }
                }

                // 3. ลบรายการสินค้าเดิมทิ้งให้หมด (เดี๋ยวสร้างใหม่จาก $items)
                StockInDetail::where('stock_in_id', $this->editingId)->delete();

                // 4. อัปเดตหัวบิล
                $stockIn->update([
                    'import_date' => $this->import_date,
                    'supplier_name' => $this->supplier_name,
                    'payment_type' => $this->payment_type,
                    'total_amount' => $this->grandTotal, // ใช้ Computed Property
                    // user_id ไม่ต้องแก้ (หรือจะแก้เป็นคนล่าสุดก็ได้)
                ]);
            }
            // กรณี: สร้างใหม่ (Create)
            else {
                $stockIn = StockIn::create([
                    'import_date' => $this->import_date,
                    'supplier_name' => $this->supplier_name,
                    'payment_type' => $this->payment_type,
                    'total_amount' => $this->grandTotal,
                    'user_id' => Auth::user()->id,
                ]);
            }

            // 5. บันทึกรายการสินค้าใหม่ (เหมือนกันทั้ง Create และ Update)
            foreach ($this->items as $item) {
                StockInDetail::create([
                    'stock_in_id' => $stockIn->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'unit_cost' => $item['unit_cost'],
                    'total_cost' => $item['qty'] * $item['unit_cost'],
                ]);

                // 6. *** บวกสต็อกใหม่เข้าไป ***
                $product = Product::find($item['product_id']);
                $product->increment('stock_qty', $item['qty']); // บวกเพิ่ม
                $product->update(['cost' => $item['unit_cost']]); // อัปเดตราคาล่าสุด
            }
        });

        $this->dispatch('close-stock-modal');
        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'แก้ไขข้อมูลเรียบร้อย' : 'บันทึกข้อมูลเรียบร้อย');

        $this->reset(['editingId']); // ล้างสถานะแก้ไข
    }

    // เพิ่มฟังก์ชันนี้ลงไปครับ (Computed Property)
    // ฟังก์ชันนี้จะทำหน้าที่รวมยอดเงินของสินค้าทุกตัวในตะกร้า
    public function getGrandTotalProperty()
    {
        // ใช้ Collect ของ Laravel ช่วยบวกเลข (สะอาดและเร็วกว่าวน Loop เอง)
        return collect($this->items)->sum(function ($item) {
            return (float)$item['qty'] * (float)$item['unit_cost'];
        });
    }

    public function render()
    {
        $products = [];

        if (!empty($this->search_product)) {
            $products = Product::where('name', 'like', "%{$this->search_product}%")
                ->take(5)
                ->orderBy('id', 'desc')
                ->get();
        }

        $history = StockIn::with('user')->orderBy('id', 'desc')->paginate(10);

        return view('livewire.admin.stock-in-component', [
            'products' => $products,
            'history' => $history,
        ]);
    }
}
