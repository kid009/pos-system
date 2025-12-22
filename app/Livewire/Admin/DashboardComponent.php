<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product; // ✅ Import Product Model
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

        // 3. Total Profit
        $totalProfit = TransactionDetail::whereHas('transaction', function($q) {
                            $q->where('status', 'completed');
                        })->get()->sum(function($detail) {
                            return ($detail->price - $detail->cost) * $detail->quantity;
                        });

        // 🔥 4. Average Order Value (AOV) - ยอดขายเฉลี่ยต่อบิล (คำนวณจากเดือนนี้)
        $monthlyTxCount = Transaction::whereYear('created_at', Carbon::now()->year)
                            ->whereMonth('created_at', Carbon::now()->month)
                            ->where('status', 'completed')
                            ->count();
        $aov = $monthlyTxCount > 0 ? $monthlySales / $monthlyTxCount : 0;

        // 🔥 5. Low Stock Alert - สินค้าเหลือน้อยกว่า 10 ชิ้น
        $lowStockItems = Product::where('stock_qty', '<=', 10)
                            ->orderBy('stock_qty', 'asc') // น้อยสุดขึ้นก่อน
                            ->take(5)
                            ->get();

        // 6. Top 5 Best Sellers
        $topProducts = TransactionDetail::select('product_name', DB::raw('sum(quantity) as total_qty'))
                        ->groupBy('product_name')
                        ->orderByDesc('total_qty')
                        ->take(5)
                        ->get();

        // 7. Recent Transactions
        $recentTransactions = Transaction::with('user')->latest()->take(5)->get();

        // 8. Sales Trend Chart (Last 30 Days)
        $chartLabels = [];
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $sales = Transaction::whereDate('created_at', $date)
                        ->where('status', 'completed')
                        ->sum('total_amount');
            $chartData[] = $sales;
        }

        return view('livewire.admin.dashboard-component', [
            'todaySales' => $todaySales,
            'monthlySales' => $monthlySales,
            'totalProfit' => $totalProfit,
            'aov' => $aov, // ส่งค่า AOV
            'lowStockItems' => $lowStockItems, // ส่งค่า Low Stock
            'topProducts' => $topProducts,
            'recentTransactions' => $recentTransactions,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}
