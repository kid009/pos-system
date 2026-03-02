<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ShopRoutingService
{
    /**
     * คำนวณเส้นทางว่า User ควรไปไหนต่อหลังจาก Login
     * @return string URL ที่จะ Redirect ไป
     */
    public function determineRedirectPath(User $user): string
    {
        // 1. admin (ข้ามขั้นตอนร้านค้า)
        // if ($user->role === 'admin' && $user->shops->count() === 0) {

        //     return route('dashboard');
        // }

        // $shops = $user->shops;

        // // 2. ไม่มีร้านสังกัด
        // if ($shops->isEmpty()) {

        //     Auth::logout();
        //     // โยน Exception หรือ return error path ตามต้องการ
        //     // ในที่นี้เลือก Logout แล้วส่งกลับไป Login พร้อม error
        //     return route('login') . '?error=no_shop_assigned';
        // }

        // // 3. มีร้านเดียว (Auto Select)
        // if ($shops->count() === 1) {
        //     $shop = $shops->first();
        //     $this->setShopSession($shop);

        //     // เช็ค Role ในร้านนั้นๆ เพื่อเลือกหน้าแรก
        //     if (in_array($shop->pivot->role, ['shop_owner', 'manager'])) {
        //         return route('dashboard');
        //     }

        //     return route('pos');
        // }

        // // 4. มีหลายร้าน (ไปหน้าเลือก)
        // return route('select-shop');
        return route('dashboard');
    }

    /**
     * บันทึกข้อมูลร้านค้าลง Session (Encapsulate logic การเก็บ Session ไว้ที่นี่)
     */
    protected function setShopSession($shop): void
    {
        session([
            'current_shop_id' => $shop->id,
            'current_role' => $shop->pivot->role,
            'shop_name' => $shop->name // เก็บชื่อร้านไว้โชว์ก็ดี
        ]);
    }
}
