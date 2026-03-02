<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckShopAccess
{
    public function handle(Request $request, Closure $next, $permission = null): Response
    {
        $user = Auth::user();
        $shopId = session('current_shop_id');
        $attemptedUrl = $request->fullUrl();

        // 1. ถ้าเป็น Global Admin ให้ผ่านฉลุยทุกหน้า
        if ($user->role === 'admin') {
            return $next($request);
        }
        Log::info('before check shop access');
        //
        // 2. ถ้าเป็นแค่ Staff (พนักงานขาย) ห้ามเข้าหน้าที่ต้องจัดการ
        // (หรือจะเช็คจาก Permission ที่ส่งมาก็ได้)
        if ($user->hasRole('staff')) {

            // 📝 บันทึก Log แจ้งเตือนความปลอดภัย (พยายามเข้าหน้าต้องห้าม)
            Log::warning('Security: Unauthorized Access Attempt (Staff)', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'shop_id' => $shopId,
                'attempted_url' => $attemptedUrl,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // แจ้งเตือนและเด้งไปหน้า POS ทันที
            // หมายเหตุ: ถ้าใช้ Livewire อาจจะใช้ session()->flash() หรือ dispatch event
            session()->flash('error', 'คุณเป็นพนักงาน (Staff) ไม่มีสิทธิ์เข้าถึงหน้าจัดการร้านค้า');

            return redirect()->route('pos');
        }
        Log::info('after check shop access');
        // 3. (Optional) ถ้ามีการส่งชื่อ Permission มาเช็คเจาะจง
        if ($permission && !$user->can($permission)) {

            // 📝 บันทึก Log กรณีสิทธิ์ไม่เพียงพอ
            Log::warning('Security: Permission Denied', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'shop_id' => $shopId,
                'attempted_url' => $attemptedUrl,
                'missing_permission' => $permission,
                'ip_address' => $request->ip()
            ]);

            session()->flash('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');

            return redirect()->route('pos');
        }

        return $next($request);
    }
}
