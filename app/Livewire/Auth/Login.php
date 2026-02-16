<?php

namespace App\Livewire\Auth;

use App\Services\Auth\LoginService;
use App\Services\Auth\ShopRoutingService;
use App\Services\LogService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Login')]
class Login extends Component
{
    public $email = '';
    public $password = '';

    protected LoginService $loginService;
    protected ShopRoutingService $shopRoutingService;

    public function boot(LoginService $loginService, ShopRoutingService $shopRoutingService)
    {
        $this->loginService = $loginService;
        $this->shopRoutingService = $shopRoutingService;
    }

    public function login()
    {
        // 1. Validation
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try
        {
            // 2. เรียกใช้ Service เพื่อ Authenticate (LoginService จะจัดการ RateLimit และ Auth::attempt)
            // ส่ง $this->remember ไปด้วยถ้ามี
            $this->loginService->authenticate($this->email, $this->password);

            // 3. ดึง User ที่เพิ่ง Login ผ่าน
            $user = Auth::user();

            // 📝 Log ดูค่า Role จริงๆ ใน Database (เพื่อ Debug)
            Log::info("Login Success: {$user->email} | DB Role: " . ($user->role ?? 'null'));

            if ($user->role === 'admin')
            {
                session([
                    'current_role' => 'admin',
                    'is_admin' => true
                ]);
                session()->save(); // บังคับเขียน Session เดี๋ยวนี้
                Log::info("Session Forced: current_role = admin");
            }

            // 4. เรียกใช้ Service เพื่อหาเส้นทาง (Dashboard หรือ POS หรือ Select Shop)
            $redirectUrl = $this->shopRoutingService->determineRedirectPath($user);

            // 5. กรณีพิเศษ: ไม่มีร้านสังกัด (Service อาจจะส่ง error param มา)
            if (str_contains($redirectUrl, 'error=no_shop_assigned'))
            {
                $this->addError('email', 'บัญชีของคุณยังไม่มีร้านค้าที่สังกัด กรุณาติดต่อผู้ดูแลระบบ');
                return;
            }

            // 6. Redirect ไปยังหน้าที่เหมาะสม
            return redirect()->to($redirectUrl);
        }
        catch (ValidationException $e)
        {
            // จับ Error จาก Validation (เช่น รหัสผิด หรือ Rate Limit) ที่โยนมาจาก Service
            // นำ Error message มาใส่ใน Field email เพื่อแสดงผลหน้า Blade
            $this->addError('email', $e->getMessage());

        }
        catch (Exception $e)
        {
            LogService::error('Login System Error', $e, [
                'email_attempt' => $this->email
            ]);

            $this->addError('email', 'เกิดข้อผิดพลาดของระบบ กรุณาลองใหม่ภายหลัง');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
