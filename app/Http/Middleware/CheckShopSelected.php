<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckShopSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. ถ้ายังไม่ Login ให้ไป Login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. ข้อยกเว้น: หน้าเลือกร้านและ Logout ให้ผ่านไปเลยโดยไม่ต้องเช็ค Shop
        // ❌ เอา $request->is('admin/*') ออกแล้ว เพื่อให้หน้าอย่าง admin/products ถูกเช็คสิทธิ์อย่างถูกต้อง
        if ($request->routeIs('select-shop') || $request->is('select-shop') || $request->routeIs('logout')) {
            return $next($request);
        }

        // 3. เช็ค Session ร้านค้า
        if (!session()->has('current_shop_id')) {

            if ($user->role === 'admin') {
                // 🔹 กรณี Admin: มีอำนาจทุกร้าน ต้องให้เลือกร้านก่อนจัดการข้อมูล
                Log::warning('Security/Flow: Admin Shop Selection Missing', [
                    'user_id' => $user->id,
                    'attempted_url' => $request->fullUrl()
                ]);
                return redirect()->route('select-shop');

            } else {
                // 🔹 กรณี 1 คน 1 ร้าน (Owner, Staff): Auto-Select อัตโนมัติ!
                $shop = $user->shops()->first();

                if ($shop) {
                    // ยัด Shop ID ลง Session ให้เลย (User ไม่ต้องกดเลือกร้านเอง)
                    session(['current_shop_id' => $shop->id]);
                } else {
                    // ป้องกันบั๊ก: ถ้าพนักงานคนนี้ยังไม่มีร้าน ให้เด้งออกและแจ้งเตือน
                    Auth::logout();
                    session()->invalidate();
                    session()->regenerateToken();

                    return redirect()->route('login')->with('error', 'บัญชีของคุณยังไม่ได้ผูกกับร้านค้าใดเลย กรุณาติดต่อผู้ดูแลระบบ');
                }
            }
        }

        $shopId = session('current_shop_id');

        // 4. สำคัญมาก: แจ้ง Spatie ว่าตอนนี้ User กำลังทำงานอยู่ในร้าน (Team) ไหน
        setPermissionsTeamId($shopId);

        // 📝 บันทึก Log: ประวัติการเข้าถึง (เปิด Debug mode ดูได้)
        Log::debug('Access: Shop Context', [
            'user_id' => $user->id,
            'shop_id' => $shopId,
            'accessed_url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);

        return $next($request);
    }
}
