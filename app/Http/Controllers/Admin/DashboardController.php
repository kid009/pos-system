<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shopId = $user->shop_id;

        // --- 1. ยอดขายวันนี้ ---
        $todayQuery = Transaction::whereDate('transaction_date', Carbon::today())
            ->where('status', 'completed');
        if ($user->role !== 'admin') {
            $todayQuery->where('shop_id', $shopId);
        }

        $todaySales = $todayQuery->sum('total_amount');
        $todayTransactionsCount = $todayQuery->count();

        // --- 2. ยอดขายเดือนนี้ ---
        $monthQuery = Transaction::whereMonth('transaction_date', Carbon::now()->month)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->where('status', 'completed');
        if ($user->role !== 'admin') {
            $monthQuery->where('shop_id', $shopId);
        }
        $monthSales = $monthQuery->sum('total_amount');

        // --- 3. จำนวนสินค้าทั้งหมด ---
        $productsCount = Product::count();

        // --- 4. รายการขายล่าสุด 10 รายการ ---
        // 🚨 ปรับ latest() ให้เรียงตาม transaction_date แทน created_at
        $recentTransactionsQuery = Transaction::with(['shop', 'cashier'])->latest('transaction_date');
        if ($user->role !== 'admin') {
            $recentTransactionsQuery->where('shop_id', $shopId);
        }
        $recentTransactions = $recentTransactionsQuery->limit(10)->get();

        // --- 5. สินค้าขายดี 5 อันดับแรก (Top Selling Products) ---
        $topProductsQuery = TransactionDetail::select('product_name', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_amount'))
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'completed');

        if ($user->role !== 'admin') {
            $topProductsQuery->where('transactions.shop_id', $shopId);
        }

        $topProducts = $topProductsQuery->groupBy('product_id', 'product_name')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        // --- 6. ข้อมูลสำหรับกราฟ (ยอดขาย 7 วันล่าสุด) ---
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            // 🚨 ค้นหาด้วย transaction_date
            $query = Transaction::whereDate('transaction_date', $date)->where('status', 'completed');
            if ($user->role !== 'admin') {
                $query->where('shop_id', $shopId);
            }
            $chartData[] = [
                'date' => $date->format('d/m'),
                'amount' => $query->sum('total_amount')
            ];
        }

        return view('dashboard', [
            'todaySales' => $todaySales,
            'todayTransactionsCount' => $todayTransactionsCount,
            'monthSales' => $monthSales,
            'productsCount' => $productsCount,
            'recentTransactions' => $recentTransactions,
            'topProducts' => $topProducts,
            'chartData' => $chartData,
        ]);
    }
}
