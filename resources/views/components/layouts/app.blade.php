<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'POS System' }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <div class="d-flex" id="wrapper">
        <div class="bg-primary text-white p-3" style="width: 250px; min-height: 100vh;">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
                <i class="fas fa-laugh-wink me-2"></i>POS System
            </div>
            <div class="list-group list-group-flush my-3">
                <a href="#" class="list-group-item list-group-item-action bg-transparent text-white fw-bold">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-transparent text-white fw-bold">
                    <i class="fas fa-box me-2"></i>Products
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-transparent text-white fw-bold">
                    <i class="fas fa-shopping-cart me-2"></i>POS (Sale)
                </a>
            </div>
        </div>
        <div id="page-content-wrapper" class="w-100">
            <nav class="navbar navbar-expand-lg navbar-light bg-white py-4 px-4 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                    <h2 class="fs-2 m-0">Dashboard</h2>
                </div>
            </nav>

            <div class="container-fluid px-4 py-4">
                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>
