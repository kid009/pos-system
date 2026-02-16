<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Services\LogService;

class LoginService
{
    /**
     * ดำเนินการเข้าสู่ระบบ
     * @throws ValidationException
     */
    public function authenticate(string $email, string $password): void
    {
        $throttleKey = $this->throttleKey($email);

        // 1. Check Rate Limit (ป้องกัน Brute Force)
        $this->ensureIsNotRateLimited($throttleKey, $email);

        // 2. Attempt Login
        if (!Auth::attempt(['email' => $email, 'password' => $password, 'is_active' => true])) {

            RateLimiter::hit($throttleKey);

            LogService::warning('Login Failed: Invalid Credentials', ['email' => $email]);

            throw ValidationException::withMessages([
                'email' => 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง', // ใช้ Lang file เพื่อรองรับหลายภาษา
            ]);
        }

        // 3. Login Success
        RateLimiter::clear($throttleKey);
        session()->regenerate();

        LogService::info('User Login Success', ['user_id' => Auth::id()]);
    }

    /**
     * ตรวจสอบว่าโดนระงับการใช้งานชั่วคราวหรือไม่
     */
    protected function ensureIsNotRateLimited(string $key, string $email): void
    {
        if (!RateLimiter::tooManyAttempts($key, 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($key);

        LogService::warning('Login Rate Limited', ['email' => $email]);

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * สร้าง Key สำหรับ Rate Limiter
     */
    protected function throttleKey(string $email): string
    {
        return Str::transliterate(Str::lower($email) . '|' . request()->ip());
    }
}
