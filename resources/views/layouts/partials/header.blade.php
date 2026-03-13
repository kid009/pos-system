<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">
        POS System
    </a>

    <button class="navbar-toggler position-absolute d-md-none collapsed"
            type="button"
            @click="sidebarOpen = !sidebarOpen"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="w-100"></div> <div class="navbar-nav px-3">
        <div class="nav-item text-nowrap position-relative" x-data="{ userMenuOpen: false }">
            <button @click="userMenuOpen = !userMenuOpen" class="nav-link bg-transparent border-0 d-flex align-items-center">
                <span data-feather="user" class="me-1"></span>
                {{ auth()->user()->name ?? 'ผู้ใช้งาน' }}
                <span data-feather="chevron-down" class="ms-1" style="width: 14px; height: 14px;"></span>
            </button>

            <div x-show="userMenuOpen"
                 @click.outside="userMenuOpen = false"
                 x-transition.opacity.duration.200ms
                 class="position-absolute end-0 bg-white border rounded shadow-sm py-2 mt-1"
                 style="min-width: 150px; z-index: 1050; display: none;">

                <div class="px-3 py-1 text-muted small">
                    สิทธิ์: <span class="badge bg-info text-dark">{{ strtoupper(auth()->user()->role ?? 'STAFF') }}</span>
                </div>
                <hr class="dropdown-divider">

                <a class="dropdown-item" href="#">ตั้งค่าโปรไฟล์</a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">ออกจากระบบ</button>
                </form>
            </div>
        </div>
    </div>
</header>
