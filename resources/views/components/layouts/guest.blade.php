<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login - POS System' }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-primary"> <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-white text-center py-4">
                        <h3 class="font-weight-light my-2">POS System Login</h3>
                    </div>
                    <div class="card-body p-5">
                        {{ $slot }}
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small text-muted">Version 1.0 (Production Ready)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
