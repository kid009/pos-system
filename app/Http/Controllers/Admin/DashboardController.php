<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Shop;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedShopId = $request->get('shop_id');

        // จัดการสิทธิ์และตัวกรองร้านค้า
        // Staff: บังคับให้ดูเฉพาะร้านตัวเอง
        // Admin: ดูได้ทุกร้าน (ถ้ายัด shop_id มาก็กรองตามนั้น)
        $filterShopId = $user->role === 'admin' ? $selectedShopId : $user->shop_id;

        // --- 1. ยอดขายวันนี้ ---
        $todayQuery = Transaction::whereDate('transaction_date', Carbon::today())
            ->where('status', 'completed');
        
        if ($filterShopId) {
            $todayQuery->where('shop_id', $filterShopId);
        }

        $todaySales = $todayQuery->sum('total_amount');
        $todayTransactionsCount = $todayQuery->count();

        // --- 1.1 ยอดขายแยกตามประเภทการชำระเงิน (วันนี้) ---
        $paymentSummaryQuery = Transaction::select('payment_method', DB::raw('SUM(total_amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->whereDate('transaction_date', Carbon::today())
            ->where('status', 'completed');

        if ($filterShopId) {
            $paymentSummaryQuery->where('shop_id', $filterShopId);
        }
        $paymentSummary = $paymentSummaryQuery->groupBy('payment_method')->get();

        // --- 2. ยอดขายเดือนนี้ ---
        $monthQuery = Transaction::whereMonth('transaction_date', Carbon::now()->month)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->where('status', 'completed');

        if ($filterShopId) {
            $monthQuery->where('shop_id', $filterShopId);
        }
        $monthSales = $monthQuery->sum('total_amount');

        // --- 3. จำนวนสินค้าทั้งหมด ---
        $productQuery = Product::query();
        if ($filterShopId) {
            $productQuery->where('shop_id', $filterShopId);
        }
        $productsCount = $productQuery->count();

        // --- 4. รายการขายล่าสุด 10 รายการ ---
        $recentTransactionsQuery = Transaction::with(['shop', 'cashier', 'customer'])->latest('transaction_date');
        
        if ($filterShopId) {
            $recentTransactionsQuery->where('shop_id', $filterShopId);
        }
        $recentTransactions = $recentTransactionsQuery->limit(10)->get();

        // --- 5. สินค้าขายดี 5 อันดับแรก (Top Selling Products) ---
        $topProductsQuery = TransactionDetail::select('product_name', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_amount'))
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'completed');

        if ($filterShopId) {
            $topProductsQuery->where('transactions.shop_id', $filterShopId);
        }

        $topProducts = $topProductsQuery->groupBy('product_id', 'product_name')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        // --- 6. ข้อมูลสำหรับกราฟ (ยอดขาย 7 วันล่าสุด) ---
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $query = Transaction::whereDate('transaction_date', $date)->where('status', 'completed');
            
            if ($filterShopId) {
                $query->where('shop_id', $filterShopId);
            }
            
            $chartData[] = [
                'date' => $date->format('d/m'),
                'amount' => $query->sum('total_amount')
            ];
        }

        // ดึงรายชื่อร้านค้าสำหรับ Dropdown (เฉพาะ Admin)
        $shops = $user->role === 'admin' ? Shop::where('is_active', true)->get() : [];

        return view('dashboard', [
            'todaySales' => $todaySales,
            'todayTransactionsCount' => $todayTransactionsCount,
            'monthSales' => $monthSales,
            'productsCount' => $productsCount,
            'recentTransactions' => $recentTransactions,
            'topProducts' => $topProducts,
            'chartData' => $chartData,
            'shops' => $shops,
            'selectedShopId' => $selectedShopId,
            'paymentSummary' => $paymentSummary
        ]);
    }
}
