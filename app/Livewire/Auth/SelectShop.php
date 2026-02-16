<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SelectShop extends Component
{
    public function selectShop($shopId)
    {
        $user = Auth::user();

        // 1. ถ้าเป็น Admin ให้ข้ามการเช็ค belongsToShop (เพราะ Admin ควรเข้าได้ทุกร้าน - Optional)
        // หรือถ้าต้องการเคร่งครัด ก็เช็คเหมือนเดิม
        if ($user->role !== 'admin' && !$user->belongsToShop($shopId)) {
            $this->dispatch('notify', message: 'คุณไม่มีสิทธิ์เข้าถึงร้านนี้', type: 'error');
            return;
        }

        // 2. ดึง Role ในร้านนั้น (ถ้าเป็น Admin ให้ถือว่าเป็น shop_owner ในบริบทร้านนั้น หรือ role พิเศษ)
        $role = $user->getRoleInShop($shopId);

        // กรณี Admin อาจจะไม่มี record ใน shop_user แต่เราอยากให้เข้าไปดูได้
        if ($user->role === 'admin') {
            $role = 'admin'; // Override role ใน session
        }

        // ✅ หัวใจสำคัญ: เก็บข้อมูลร้านปัจจุบันลง Session
        session(['current_shop_id' => $shopId]);
        session(['current_role' => $role]);

        // 4. Redirect
        return redirect()->route('dashboard');
    }

    // ✅ ฟังก์ชันใหม่: ไปหน้า Global Dashboard (สำหรับ Admin เท่านั้น)
    public function goToAdminDashboard()
    {
        if (Auth::user()->role === "admin") {
            // เคลียร์ Session ร้านค้า (เพื่อให้รู้ว่ากำลังดูภาพรวม
            // session()->forget(['current_shop_id', 'current_role']);
            return redirect()->route('admin.global-dashboard');
        } else {
            $this->dispatch('notify', message: 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้', type: 'error');
        }
    }

    public function render()
    {
        $user = Auth::user();

        // ถ้าเป็น Admin ดึงร้านทั้งหมดมาโชว์เลย (หรือจะเอาเฉพาะที่ผูกก็ได้)
        // ในที่นี้เอาเฉพาะที่ผูกก่อน เพื่อความปลอดภัย
        $shops = $user->shops;

        return view('livewire.auth.select-shop', [
            'shops' => $shops,
            'isAdmin' => $user->role === 'admin', // ส่งตัวแปรไปเช็คหน้า View
        ]);
    }
}
