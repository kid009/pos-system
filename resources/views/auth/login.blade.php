@extends('layouts.guest')

@section('content')
<x-auth-session-status class="mb-4" :status="session('status')" />

<div class="login-card">
    <form class="theme-form login-form" method="POST" action="{{ route('login') }}">
        @csrf

        <h4>{{ __('Login') }}</h4>
        <h6>{{ __('Welcome back! Log in to your account.') }}</h6>

        <div class="form-group">
            <label for="email">{{ __('Email Address') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="icon-email"></i></span>
                <input id="email" class="form-control" type="email" name="email" :value="old('email')" required
                    autofocus autocomplete="username" placeholder="Test@gmail.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="form-group">
            <label for="password">{{ __('Password') }}</label>
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
                {{ __('Sign in') }}
            </button>
        </div>

    </form>
</div>
@endsection