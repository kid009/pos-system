<div class="d-flex" id="wrapper">
    <div class="bg-primary text-white p-3" style="width: 250px; min-height: 100vh;">
        <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
            <i class="fas fa-laugh-wink me-2"></i>ร้านแก๊ส
        </div>
        <div class="list-group list-group-flush my-3">

            <a href="{{ route('pos') }}"
                class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('pos') ? 'active-link' : '' }}">
                <i class="fas fa-shopping-cart me-2"></i>หน้าขายสินค้า
            </a>

            @if (session('current_role') === 'shop_owner' || session('current_role') === 'admin')
                <a href="{{ route('dashboard') }}"
                    class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>

                <a href="{{ route('admin.users') }}"
                    class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="fas fa-tags me-2"></i>จัดการพนักงาน
                </a>

                <a href="{{ route('admin.shops') }}"
                    class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('admin.shops') ? 'active' : '' }}">
                    <i class="fas fa-tags me-2"></i>จัดการร้านค้า
                </a>

                <a href="{{ route('admin.categories') }}"
                    class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                    <i class="fas fa-tags me-2"></i>ประเภทสินค้า
                </a>

                <a href="{{ route('admin.products') }}"
                    class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                    <i class="fas fa-box me-2"></i>สินค้า
                </a>

                <a href="{{ route('admin.stock-in') }}"
                    class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('admin.stock-in') ? 'active-link' : '' }}">
                    <i class="fas fa-users me-2"></i>นำเข้าสินค้า
                </a>

                <a href="{{ route('admin.customers') }}"
                    class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('admin.customers') ? 'active-link' : '' }}">
                    <i class="fas fa-users me-2"></i>รายชื่อลูกค้า
                </a>

                <a href="{{ route('admin.expense-categories') }}"
                    class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('admin.expense-categories') ? 'active-link' : '' }}">
                    <i class="fas fa-users me-2"></i>ประเภทค่าใช้จ่าย
                </a>
            @endif

            <a href="{{ route('sales-report') }}"
                class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('sales-report') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i>รายงานการขาย
            </a>

            <a href="{{ route('admin.transaction-history') }}"
                class="list-group-item list-group-item-action bg-transparent text-white fw-bold {{ request()->routeIs('admin.transaction-history') ? 'active-link' : '' }}">
                <i class="fas fa-users me-2"></i>ประวัติรายการขาย
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
