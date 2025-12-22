<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardComponent extends Component
{
    public function render()
    {
        // 1. Today's Sales
        $todaySales = Transaction::whereDate('created_at', Carbon::today())
                        ->where('status', 'completed')
                        ->sum('total_amount');

        // 2. Monthly Sales
        $monthlySales = Transaction::whereYear('created_at', Carbon::now()->year)
                        ->whereMonth('created_at', Carbon::now()->month)
                        ->where('status', 'completed')
                        ->sum('total_amount');

        // 🔥 3. Total Profit (Optimized: คำนวณด้วย SQL โดยตรง ไม่โหลดเข้า PHP)
        $totalProfit = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                        ->where('transactions.status', 'completed')
                        ->sum(DB::raw('(transaction_details.price - transaction_details.cost) * transaction_details.quantity'));

        // 4. Average Order Value (AOV)
        $monthlyTxCount = Transaction::whereYear('created_at', Carbon::now()->year)
                            ->whereMonth('created_at', Carbon::now()->month)
                            ->where('status', 'completed')
                            ->count();
        $aov = $monthlyTxCount > 0 ? $monthlySales / $monthlyTxCount : 0;

        // 5. Low Stock Alert
        $lowStockItems = Product::where('stock_qty', '<=', 10)
                            ->orderBy('stock_qty', 'asc')
                            ->take(5)
                            ->get();

        // 6. Top 5 Best Sellers (Optimized: Group ID เพื่อความชัวร์)
        $topProducts = TransactionDetail::select('product_name', DB::raw('sum(quantity) as total_qty'))
                        ->groupBy('product_name') // ถ้า MySQL Error ให้เพิ่ม product_id
                        ->orderByDesc('total_qty')
                        ->take(5)
                        ->get();

        // 7. Recent Transactions
        $recentTransactions = Transaction::with('user')->latest()->take(5)->get();

        // 🔥 8. Sales Trend Chart (Optimized: Query ครั้งเดียว ไม่วนลูป Query)
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(29);

        // ดึงข้อมูล 30 วันรวดเดียว (Group ตามวันที่)
        $salesData = Transaction::select(
                            DB::raw('DATE(created_at) as date'),
                            DB::raw('SUM(total_amount) as total')
                        )
                        ->where('created_at', '>=', $startDate)
                        ->where('status', 'completed')
                        ->groupBy('date')
                        ->pluck('total', 'date') // ได้ Array แบบ ['2023-12-01' => 500, ...]
                        ->toArray();

        $chartLabels = [];
        $chartData = [];

        // วนลูปเพื่อจัด Format (ไม่ได้ Query แล้ว เร็วปรู๊ด)
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->format('d M');

            // ถ้าวันไหนไม่มีขาย ให้ใส่ 0 (ใช้ null coalescing operator)
            $chartData[] = $salesData[$date] ?? 0;
        }

        return view('livewire.admin.dashboard-component', [
            'todaySales' => $todaySales,
            'monthlySales' => $monthlySales,
            'totalProfit' => $totalProfit,
            'aov' => $aov,
            'lowStockItems' => $lowStockItems,
            'topProducts' => $topProducts,
            'recentTransactions' => $recentTransactions,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}
