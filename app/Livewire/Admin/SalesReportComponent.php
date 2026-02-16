<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class SalesReportComponent extends Component
{
    // ตัวแปรสำหรับ Filter
    public $start_date;
    public $end_date;

    public function mount()
    {
        // ค่าเริ่มต้น: ตั้งแต่วันที่ 1 ของเดือนนี้ ถึง วันนี้
        $this->start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = Carbon::today()->format('Y-m-d');
    }

    public function render()
    {
        // Query ข้อมูลตามช่วงเวลาที่เลือก
        $transactions = Transaction::with(['user', 'customer']) // ดึงข้อมูลคนขายและลูกค้ามาด้วย
                        ->whereDate('created_at', '>=', $this->start_date)
                        ->whereDate('created_at', '<=', $this->end_date)
                        ->where('status', 'completed') // เอาเฉพาะบิลที่สำเร็จ
                        ->orderBy('created_at', 'desc') // ใหม่สุดขึ้นก่อน
                        ->get();

        // คำนวณยอดรวมจากผลลัพธ์ที่กรองได้
        $totalSales = $transactions->sum('total_amount');

        return view('livewire.admin.sales-report-component', [
            'transactions' => $transactions,
            'totalSales' => $totalSales
        ]);
    }
}
