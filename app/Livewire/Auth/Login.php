<?php

namespace App\Livewire\Auth;

use App\Services\LogService;
use Exception;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

#[Layout('components.layouts.guest')]
#[Title('Login')]
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    public function login()
    {
        // 1. Validation
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            // 2. Rate Limiting (ป้องกันการเดารหัสผ่านรัวๆ)
            $throttleKey = Str::lower($this->email) . '|' . request()->ip();
            if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
                $seconds = RateLimiter::availableIn($throttleKey);
                $this->addError('email', "Too many login attempts. Please try again in $seconds seconds.");

                LogService::warning('Login Brute Force Attempt', [
                    'email_attempt' => $this->email,
                ]);

                return;
            }

            // 3. Attempt Login
            if (!Auth::attempt(['email' => $this->email, 'password' => $this->password, 'is_active' => true], $this->remember)) {
                RateLimiter::hit($throttleKey); // นับจำนวนครั้งที่ผิด
                $this->addError('email', 'These credentials do not match our records.');

                LogService::warning('Login Failed: Invalid Credentials', [
                    'email_attempt' => $this->email
                ]);
                return;
            }

            LogService::info('User Login Success', [
                'role' => auth()->user()->role
            ]);

            // 4. Login Success -> Clear Rate Limiter
            RateLimiter::clear($throttleKey);
            session()->regenerate(); // ป้องกัน Session Fixation Attack

            // 5. Redirect based on Role
            return $this->redirectBasedOnRole();
        } catch (Exception $e) {
            LogService::error('Login System Error', $e, [
                'email_attempt' => $this->email
            ]);

            $this->addError('email', 'System Error');
        }
    }

    private function redirectBasedOnRole()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->intended('/dashboard');
        }

        return redirect()->intended('/pos');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
