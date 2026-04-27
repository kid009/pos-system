<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar" :class="{ 'sidebar-open': sidebarOpen }">
    <div class="position-sticky pt-3 sidebar-sticky">

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <span data-feather="home"></span>
                    แผงควบคุม (Dashboard)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                    <span data-feather="monitor"></span>
                    หน้าจอขาย (POS)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}"
                    href="{{ route('transactions.index') }}">
                    <span data-feather="file-text"></span>
                    ประวัติการขาย
                </a>
            </li>
        </ul>

        @if (in_array(auth()->user()->role, ['admin', 'owner']))
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>การจัดการระบบ</span>
            </h6>
            <ul class="nav flex-column mb-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}"
                        href="{{ route('shop.index') }}">
                        <span data-feather="shopping-bag"></span>
                        จัดการร้านค้า
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('product-categories.*') ? 'active' : '' }}"
                        href="{{ route('product-categories.index') }}">
                        <span data-feather="grid"></span>
                        หมวดหมู่สินค้า
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                        href="{{ route('products.index') }}">
                        <span data-feather="package"></span>
                        จัดการสินค้า
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"
                        href="{{ route('customers.index') }}">
                        <span data-feather="users"></span>
                        ฐานข้อมูลลูกค้า
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span data-feather="dollar-sign"></span>
                        รายรับ-รายจ่าย
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('banks.*') ? 'active' : '' }}"
                        href="{{ route('banks.index') }}">
                        <span data-feather="credit-card"></span>
                        ข้อมูลธนาคาร
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shipping-methods.*') ? 'active' : '' }}"
                        href="{{ route('shipping-methods.index') }}">
                        <span data-feather="truck"></span>
                        ข้อมูลขนส่ง
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sales-channels.*') ? 'active' : '' }}"
                        href="{{ route('sales-channels.index') }}">
                        <span data-feather="shopping-cart"></span>
                        ช่องทางการขาย
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}"
                        href="{{ route('suppliers.index') }}">
                        <span data-feather="briefcase"></span>
                        ซัพพลายเออร์
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}"
                        href="{{ route('purchases.index') }}">
                        <span data-feather="briefcase"></span>
                        รับเข้าสินค้า
                    </a>
                </li>
            </ul>
        @endif

        @if (auth()->user()->role === 'admin')
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-danger">
                <span>สำหรับผู้ดูแลระบบ</span>
            </h6>
            <ul class="nav flex-column ">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }} text-danger"
                        href="{{ route('users.index') }}">
                        <span data-feather="settings"></span>
                        จัดการผู้ใช้งาน (Users)
                    </a>
                </li>
            </ul>
            <ul class="nav flex-column ">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('system-logs.*') ? 'active' : '' }} text-danger"
                        href="{{ route('system-logs.index') }}">
                        <span data-feather="settings"></span>
                        Log Activities
                    </a>
                </li>
            </ul>
        @endif

    </div>
</nav>

<div x-show="sidebarOpen" @click="sidebarOpen = false"
    class="d-md-none position-fixed top-0 start-0 w-100 h-100 bg-dark"
    style="opacity: 0.5; z-index: 99; display: none;">
</div>
