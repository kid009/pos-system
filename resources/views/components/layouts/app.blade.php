<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'POS System' }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-light bg-white py-2 px-4 shadow-sm mb-4">
        <div class="d-flex align-items-center w-100 justify-content-between">

            <div class="d-flex align-items-center">
                <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle" style="cursor: pointer;"></i>
                <h2 class="fs-4 m-0 text-secondary">{{ $title ?? 'Dashboard' }}</h2>
            </div>

            <div x-data="{ userMenuOpen: false, logoutModalOpen: false }">

                <nav class="navbar navbar-expand-lg navbar-light bg-white py-2 px-4 shadow-sm mb-4">
                    <div class="d-flex align-items-center w-100 justify-content-between">

                        <div class="dropdown">
                            <button
                                class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center text-dark"
                                type="button" @click="userMenuOpen = !userMenuOpen"
                                @click.outside="userMenuOpen = false" aria-expanded="false">

                                <div class="me-2 text-end d-none d-lg-block">
                                    <span class="small text-gray-600 d-block">Welcome,</span>
                                    <span class="fw-bold text-primary">{{ Auth::user()->name ?? 'Guest' }}</span>
                                </div>
                                <img class="img-profile rounded-circle border"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=0D6EFD&color=fff"
                                    style="width: 40px; height: 40px;">
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                :class="{ 'show': userMenuOpen }" style="min-width: 200px;">

                                <li>
                                    <h6 class="dropdown-header">User Menu</h6>
                                </li>
                                <li><a class="dropdown-item" href="#"><i
                                            class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i> Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                        @click.prevent="logoutModalOpen = true; userMenuOpen = false">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <template x-if="logoutModalOpen">
                    <div class="modal fade show d-block" tabindex="-1" role="dialog"
                        style="background-color: rgba(0,0,0,0.5);">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">
                                        <i class="fas fa-exclamation-triangle me-2"></i> Confirm Logout
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        @click="logoutModalOpen = false"></button>
                                </div>
                                <div class="modal-body py-4 text-center">
                                    <h5 class="mb-2">Are you sure you want to leave?</h5>
                                    <p class="text-muted mb-0">Select "Logout" below if you are ready to end your
                                        current session.</p>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-secondary px-4"
                                        @click="logoutModalOpen = false">
                                        Cancel
                                    </button>
                                    <button type="button" class="btn btn-danger px-4"
                                        onclick="document.getElementById('logout-form').submit()">
                                        Logout
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

        </div>
    </nav>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <div class="d-flex" id="wrapper">
        <div class="bg-primary text-white p-3" style="width: 250px; min-height: 100vh;">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
                <i class="fas fa-laugh-wink me-2"></i>POS System
            </div>
            <div class="list-group list-group-flush my-3">
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="{{ route('sales-report') }}" class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('sales-report') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>SalesReport
                </a>
                <a href="{{ route('admin.transaction-history') }}"
                    class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('admin.transaction-history') ? 'active-link' : '' }}">
                    <i class="fas fa-users me-2"></i>Transaction History
                </a>
                <a href="{{ route('admin.categories') }}"
                    class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                    <i class="fas fa-tags me-2"></i>Categories
                </a>

                <a href="{{ route('admin.products') }}"
                    class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                    <i class="fas fa-box me-2"></i>Products
                </a>
                <a href="{{ route('admin.stock-in') }}"
                    class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('admin.stock-in') ? 'active-link' : '' }}">
                    <i class="fas fa-users me-2"></i>Stock In
                </a>
                <a href="{{ route('admin.customers') }}"
                    class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('admin.customers') ? 'active-link' : '' }}">
                    <i class="fas fa-users me-2"></i>Customers
                </a>
                <a href="{{ route('pos') }}" class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('pos') ? 'active-link' : '' }}">
                    <i class="fas fa-shopping-cart me-2"></i>POS (Sale)
                </a>
            </div>
        </div>
        <div id="page-content-wrapper" class="w-100">
            <div class="container-fluid px-4 py-4">

                <x-alert-message />

                <x-confirm-modal />

                {{ $slot }}

            </div>
        </div>
    </div>

</body>

</html>
