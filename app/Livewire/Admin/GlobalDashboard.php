<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Shop;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;

#[Layout('components.layouts.app')] // ใช้ Layout หลัก (แต่ต้องไม่มี Sidebar ร้านค้า)
#[Title('Admin Global Dashboard')]
class GlobalDashboard extends Component
{
    public function render()
    {
        // 1. สถิติรวมทั้งระบบ
        $totalShops = Shop::count();
        $totalUsers = User::count();
        $totalTransactions = Transaction::where('status', 'completed')->count();

        // ยอดขายรวมทั้งระบบเดือนนี้
        $totalRevenueMonth = Transaction::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        // 2. รายชื่อร้านค้าล่าสุด
        $recentShops = Shop::withCount('users')
            ->latest()
            ->take(5)
            ->get();

        // 3. ยอดขายแยกตามร้าน (Top 5)
        $topShops = Shop::withSum(['transactions' => function($q) {
                $q->where('status', 'completed');
            }], 'total_amount')
            ->orderByDesc('transactions_sum_total_amount')
            ->take(5)
            ->get();

        return view('livewire.admin.global-dashboard', [
            'totalShops' => $totalShops,
            'totalUsers' => $totalUsers,
            'totalTransactions' => $totalTransactions,
            'totalRevenueMonth' => $totalRevenueMonth,
            'recentShops' => $recentShops,
            'topShops' => $topShops
        ]);
    }
}
