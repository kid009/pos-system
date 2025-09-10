@extends('layouts.guest')

@section('content')
<x-auth-session-status class="mb-4" :status="session('status')" />

<div class="login-card">
    <form class="theme-form login-form" method="POST" action="{{ route('login') }}">
        @csrf

        <h4>ร้านพีแก๊ส</h4>
        <h6>ยินดีต้อนรับเข้าสู่ระบบ</h6>

        <div class="form-group">
            <label for="email">อีเมล</label>
            <div class="input-group">
                <span class="input-group-text"><i class="icon-email"></i></span>
                <input id="email" class="form-control" type="email" name="email" :value="old('email')" required
                    autofocus autocomplete="username" placeholder="Test@gmail.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="form-group">
            <label for="password">รหัสผ่าน</label>
            <div class="input-group">
                <span class="input-group-text"><i class="icon-lock"></i></span>
                <input id="password" class="form-control" type="password" name="password" required
                    autocomplete="current-password" placeholder="*********">
                <div class="show-hide"><span class="show"></span></div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="form-group">
            <button class="btn btn-primary btn-block" type="submit">
                เข้าสู่ระบบ
            </button>
        </div>

    </form>
</div>
@endsection