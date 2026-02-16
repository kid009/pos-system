<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class TransactionHistoryComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // ตัวแปรสำหรับ Search
    public $search = '';
    public $filter_date;

    // ตัวแปรสำหรับ Modal ดูรายละเอียด
    public $viewTransaction = null;
    public $viewDetails = [];

    // ตัวแปรสำหรับ Modal แก้ไข (Payment/Customer)
    public $editingId = null;
    public $edit_payment_method;
    public $edit_received_amount;
    public $edit_customer_id;
    public $edit_status;

    public function mount()
    {
        $this->filter_date = date('Y-m-d'); // default วันนี้
    }

    // เปิด Modal ดูรายละเอียดสินค้าในบิล
    public function openViewModal($id)
    {
        $this->viewTransaction = Transaction::with(['user', 'customer', 'details'])->find($id);

        if ($this->viewTransaction) {
            $this->viewDetails = $this->viewTransaction->details;
            $this->dispatch('show-view-modal');
        }
    }

    // เปิด Modal แก้ไขข้อมูลการชำระเงิน
    public function openEditModal($id)
    {
        // 1. ลองหาข้อมูลดูก่อน (ใช้ findOrfail หรือเช็ค null)
        $transaction = Transaction::find($id);

        // 2. ถ้าหาไม่เจอ ให้หยุดทำงานเลย (กัน Error 500)
        if (!$transaction) {
            return;
        }

        // 3. กำหนดค่าตัวแปร
        $this->editingId = $id;
        $this->edit_payment_method = $transaction->payment_method;
        // แปลงยอดเงินให้เป็นตัวเลขเพียวๆ (กัน error เรื่อง format)
        $this->edit_received_amount = (float) $transaction->received_amount;
        $this->edit_customer_id = $transaction->customer_id;
        $this->edit_status = $transaction->status;

        // 4. ส่งคำสั่งเปิด Modal
        $this->dispatch('show-edit-modal');
    }

    // บันทึกการแก้ไข
    public function updateTransaction()
    {
        $this->validate([
            'edit_payment_method' => 'required',
            'edit_received_amount' => 'required|numeric|min:0',
        ]);

        $transaction = Transaction::find($this->editingId);

        if ($transaction) {
            // คำนวณเงินทอนใหม่ (เผื่อเปลี่ยนยอดรับเงิน)
            $newChange = $this->edit_received_amount - $transaction->total_amount;

            // ถ้าติดลบ (จ่ายไม่ครบ) ให้เงินทอนเป็น 0
            if ($newChange < 0) $newChange = 0;

            $transaction->update([
                'payment_method' => $this->edit_payment_method,
                'received_amount' => $this->edit_received_amount,
                'change_amount' => $newChange,
                'customer_id' => $this->edit_customer_id ?: null, // เผื่อเลือก "ลูกค้าทั่วไป"
                'status' => $this->edit_status
            ]);

            $this->dispatch('close-edit-modal');
            $this->dispatch('notify', type: 'success', message: 'แก้ไขข้อมูลเรียบร้อยแล้ว');
        }
    }

    public function render()
    {
        $customers = Customer::all(); // เอาไว้เลือกใน Dropdown ตอนแก้

        $transactions = Transaction::with(['user', 'customer'])
            ->where(function ($q) {
                $q->where('reference_no', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($c) {
                        $c->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->filter_date, function ($q) {
                $q->whereDate('created_at', $this->filter_date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.transaction-history-component', [
            'transactions' => $transactions,
            'customers' => $customers
        ]);
    }
}
