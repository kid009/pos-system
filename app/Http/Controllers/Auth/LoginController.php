<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    /**
     * แสดงหน้าฟอร์ม Login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ประมวลผลการ Login
     */
    public function processLogin(Request $request)
    {
        // 1. Validate Input เบื้องต้น
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. ส่งต่อให้ Service ทำงาน (หาก Error Service จะโยน ValidationException ออกมาเอง)
        $this->loginService->authenticate(
            $request->input('email'),
            $request->input('password'),
        );

        // 3. Redirect เมื่อสำเร็จ (ตัวอย่างไปหน้าแดชบอร์ด)
        // หมายเหตุ: ตรงนี้ในอนาคตคุณสามารถใช้ ShopRoutingService มากำหนดเส้นทางได้เหมือนเดิม
        return redirect()->intended('/dashboard');
    }

    /**
     * ออกจากระบบ
     */
    public function logout(Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
