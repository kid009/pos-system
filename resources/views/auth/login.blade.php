<x-auth-layout>
    <x-slot:title>Sign In</x-slot:title>

    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[550px]">
        <!-- Brand / Identity Visual Side (Left Column) -->
        <div
            class="hidden lg:flex lg:col-span-5 bg-slate-900 flex-col items-center justify-center p-12 text-center relative overflow-hidden">
            <!-- Decorative Subtle Pattern -->
            <div
                class="absolute inset-0 opacity-10 pointer-events-none bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:16px_16px]">
            </div>

            <div class="relative z-10 space-y-4">
                <img src="{{ asset('images/logo.png') }}" alt="System Logo"
                    class="w-32 h-auto mx-auto object-contain drop-shadow-md">
                <h2 class="text-xl font-bold tracking-tight text-white">Point of Sale System</h2>
                <p class="text-sm text-slate-400 max-w-xs mx-auto">
                    Immutable Inventory Ledger & Secure Terminal Management
                </p>
            </div>
        </div>

        <!-- Form Interaction Side (Right Column) -->
        <div class="col-span-1 lg:col-span-7 p-8 sm:p-12 flex flex-col justify-center">
            <div class="w-full max-w-md mx-auto space-y-6">

                <!-- Header -->
                <div class="text-left space-y-1">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Welcome Back!</h1>
                    <p class="text-sm text-slate-500">Sign in to your administrative account to continue.</p>
                </div>

                <!-- Session Status Messages (e.g. Password Reset Status) -->
                <x-alert />

                <form method="POST" action="{{ route('auth.login') }}" id="loginForm" class="space-y-5" novalidate>
                    @csrf

                    <!-- Email Input -->
                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-slate-700">
                            Email Address <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            autofocus autocomplete="email" placeholder="admin@company.com"
                            class="block w-full px-4 py-2.5 rounded-lg border text-sm transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                      {{ $errors->has('email') ? 'border-rose-300 bg-rose-50/30 text-rose-900 focus:ring-rose-500' : 'border-slate-300 bg-white text-slate-900' }}">
                        @error('email')
                            <p class="text-xs font-medium text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-medium text-slate-700">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required autocomplete="current-password"
                            placeholder="••••••••••••"
                            class="block w-full px-4 py-2.5 rounded-lg border text-sm transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                      {{ $errors->has('password') ? 'border-rose-300 bg-rose-50/30 text-rose-900 focus:ring-rose-500' : 'border-slate-300 bg-white text-slate-900' }}">
                        @error('password')
                            <p class="text-xs font-medium text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Primary Action Button with Guard -->
                    <div class="pt-2">
                        <button type="submit" id="submitBtn"
                            class="w-full flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-150 shadow-sm disabled:opacity-60 disabled:cursor-not-allowed">
                            <span id="btnText">Sign In</span>
                            <span id="btnSpinner" class="hidden ml-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Scoped Vanilla JavaScript: Strictly No Alpine.js -->
    <x-slot:scripts>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loginForm = document.getElementById('loginForm');
                const submitBtn = document.getElementById('submitBtn');
                const btnText = document.getElementById('btnText');
                const btnSpinner = document.getElementById('btnSpinner');

                if (loginForm && submitBtn) {
                    loginForm.addEventListener('submit', function(event) {
                        // Check HTML5 validity prior to locking
                        if (!loginForm.checkValidity()) {
                            return;
                        }

                        // Prevent multiple clicks from hammering auth rate limiters
                        submitBtn.disabled = true;
                        btnText.textContent = 'Verifying...';
                        btnSpinner.classList.remove('hidden');
                    });
                }
            });
        </script>
    </x-slot:scripts>
</x-auth-layout>
