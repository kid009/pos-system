<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class DashboardComponent extends Component
{
    public function render()
    {
        // 1. ดึง ID ร้านปัจจุบันจาก Session
        $shopId = session('current_shop_id');

        // ถ้าไม่มี shop_id (เผื่อหลุดมา) ให้กลับไปเลือก
        // if (!$shopId) {
        //     return redirect()->route('select-shop');
        // }

        // กำหนดช่วงเวลา
        $startOfDay = Carbon::today()->startOfDay();
        $endOfDay   = Carbon::today()->endOfDay();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        // ----------------------------------------------------
        // 1. Today's Sales (ยอดเงินสดเข้าวันนี้)
        // ----------------------------------------------------
        $todaySales = Transaction::where('shop_id', $shopId) // ✅ กรองร้าน
            ->whereBetween('transaction_date', [$startOfDay, $endOfDay])
            ->where('status', 'completed')
            ->sum('received_amount');

        // ----------------------------------------------------
        // 2. Monthly Sales (ยอดขายรวมเดือนนี้ - นับตามมูลค่าบิล)
        // ----------------------------------------------------
        $monthlySales = Transaction::where('shop_id', $shopId) // ✅ กรองร้าน
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->where('status', 'completed')
            ->sum('total_amount');

        // ----------------------------------------------------
        // 3. กำไรขั้นต้น (Gross Profit) - เฉพาะเดือนนี้
        // ----------------------------------------------------
        $totalProfit = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.shop_id', $shopId) // ✅ กรองร้าน
            ->where('transactions.status', 'completed')
            ->whereBetween('transactions.transaction_date', [$startOfMonth, $endOfMonth])
            ->sum(DB::raw('(transaction_details.price - transaction_details.cost) * transaction_details.quantity'));

        // ----------------------------------------------------
        // 4. ยอดลูกหนี้ (Account Receivable) - สะสมทั้งหมด
        // ----------------------------------------------------
        $accountReceivable = Transaction::where('shop_id', $shopId) // ✅ กรองร้าน
            ->where('status', 'completed')
            // หายอดที่ รับเงิน น้อยกว่า ยอดรวม (คือจ่ายไม่ครบ)
            ->whereRaw('received_amount < total_amount')
            ->sum(DB::raw('total_amount - received_amount'));

        // ----------------------------------------------------
        // 5. สรุปยอดถังแก๊ส (Cylinder Stats) - เดือนนี้
        // ----------------------------------------------------
        $cylinderStats = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.shop_id', $shopId) // ✅ กรองร้าน
            ->where('transactions.status', 'completed')
            ->whereBetween('transactions.transaction_date', [$startOfMonth, $endOfMonth])
            ->whereNotNull('gas_status')
            ->where('gas_status', '!=', '')
            ->select('gas_status', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('gas_status')
            ->pluck('total_qty', 'gas_status')
            ->toArray();

        // ----------------------------------------------------
        // 6. สินค้าขายดี (Top Products)
        // ----------------------------------------------------
        $topProducts = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.shop_id', $shopId) // ✅ กรองร้าน
            ->whereBetween('transactions.transaction_date', [$startOfMonth, $endOfMonth])
            ->where('transactions.status', 'completed')
            ->select('product_name', DB::raw('sum(quantity) as total_qty'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // ----------------------------------------------------
        // 7. สินค้าใกล้หมด (Low Stock)
        // ----------------------------------------------------
        // ต้อง Join Category เพื่อเช็คว่าเป็นสินค้าตัดสต็อกหรือไม่ (ถ้าไม่ใช่น้ำแก๊ส/บริการ)
        $lowStockItems = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->where('categories.shop_id', $shopId) // ✅ กรองร้านจากหมวดหมู่
            ->where('products.stock_qty', '<=', 10)
            ->where('products.is_active', true)
            // ยกเว้นหมวดน้ำแก๊สและบริการ (เพราะเราตั้งใจให้ติดลบได้ หรือไม่นับ)
            ->whereNotIn('categories.name', ['น้ำแก๊ส', 'บริการ', 'ค่าขนส่ง'])
            ->select('products.*')
            ->orderBy('products.stock_qty', 'asc')
            ->take(5)
            ->get();

        // ----------------------------------------------------
        // 8. กราฟยอดขาย 30 วัน
        // ----------------------------------------------------
        $startDate = Carbon::today()->subDays(29);
        $salesData = Transaction::select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('shop_id', $shopId) // ✅ กรองร้าน
            ->where('transaction_date', '>=', $startDate)
            ->where('status', 'completed')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $chartLabels = [];
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->format('d M');
            $chartData[] = $salesData[$date] ?? 0;
        }

        return view('livewire.admin.dashboard-component', [
            'todaySales' => $todaySales,
            'monthlySales' => $monthlySales,
            'totalProfit' => $totalProfit,
            'accountReceivable' => $accountReceivable,
            'cylinderStats' => $cylinderStats,
            'topProducts' => $topProducts,
            'lowStockItems' => $lowStockItems,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}
