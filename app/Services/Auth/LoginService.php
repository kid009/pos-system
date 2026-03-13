<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginService
{
    /**
     * ดำเนินการตรวจสอบการ Login พร้อมระบบป้องกัน Brute-force
     */
    public function authenticate(string $email, string $password): void
    {
        $throttleKey = $this->throttleKey($email);

        // 1. ตรวจสอบ Rate Limit (จำกัดการเข้าสู่ระบบผิดพลาด)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "คุณพยายามเข้าสู่ระบบผิดพลาดหลายครั้ง กรุณารอ {$seconds} วินาที.",
            ]);
        }

        // 2. พยายาม Authenticate
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            RateLimiter::hit($throttleKey); // บันทึกสถิติว่ารหัสผิด
            throw ValidationException::withMessages([
                'email' => __('auth.failed'), // รหัสผ่านไม่ถูกต้อง
            ]);
        }

        // 3. Clear Rate Limit เมื่อ Login สำเร็จ
        RateLimiter::clear($throttleKey);

        // 4. Setup Context Session เบื้องต้น (ตัวอย่างสำหรับ Admin)
        $this->setupInitialSessionContext(Auth::user());
    }

    private function throttleKey(string $email): string
    {
        return Str::transliterate(Str::lower($email).'|'.request()->ip());
    }

    private function setupInitialSessionContext($user): void
    {
        // ป้องกัน Session Fixation
        request()->session()->regenerate();

        if ($user->role === 'admin') {
            session(['current_role' => 'admin', 'is_admin' => true]);
        }
    }
}
