<div>
    <div>
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <form wire:submit="login">
        <div class="form-floating mb-3">
            <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror"
                id="inputEmail" placeholder="name@example.com">
            <label for="inputEmail">Email address</label>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-floating mb-3">
            <input type="password" wire:model="password" class="form-control @error('password') is-invalid @enderror"
                id="inputPassword" placeholder="Password">
            <label for="inputPassword">Password</label>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" id="inputRememberPassword" type="checkbox" wire:model="remember">
            <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
            <button type="submit" class="btn btn-primary w-100 py-2">
                <span wire:loading.remove>Login</span>
                <span wire:loading>Logging in...</span>
            </button>
        </div>
    </form>
</div>
