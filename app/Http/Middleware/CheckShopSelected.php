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
        if(!Auth::check())
        {
            return redirect()->route('login');
        }

        // ✅ 2. เพิ่มข้อยกเว้น: ถ้า URL ขึ้นต้นด้วย admin/ หรือเป็นหน้า Logout/Select Shop ให้ผ่านไปเลย
        // ไม่ต้องเช็ค shop_id
        if ($request->is('admin/*') || $request->routeIs('select-shop') || $request->routeIs('logout'))
        {
            return $next($request);
        }

        // 2. เช็ค Session
        if (!session()->has('current_shop_id')) {
            Log::warning("Middleware Block: User {$request->user()->id} tried to access {$request->path()} without shop selection.");
            return redirect()->route('select-shop');
        }

        return $next($request);
    }
}
