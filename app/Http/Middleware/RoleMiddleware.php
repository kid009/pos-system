<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // ถ้า Role ของ User ไม่อยู่ใน list ที่อนุญาต
        if (!in_array($user->role, $roles)) {
            // ถ้าเป็น Admin ให้ไป dashboard, ถ้าไม่ใช่ไป POS (หรือหน้า 403)
            if ($user->role === 'employee') {
                 return redirect('/pos'); // Redirect พนักงานหนีไปหน้าขาย
            }
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
