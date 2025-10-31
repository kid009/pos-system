<header class="main-nav">
    <div class="text-center sidebar-user">
        <img class="img-90 rounded-circle" src="{{ asset('admin_assets/images/dashboard/1.png') }}" alt="">
        <p>
        <h6 class="mt-3 f-14 f-w-600">Emay Walter</h6>
        </p>
    </div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                                aria-hidden="true"></i></div>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav {{ request()->routeIs('admin.home') ? 'active' : '' }}"
                            href="{{ route('admin.home') }}">
                            <i data-feather="home"></i><span>Home</span>
                        </a>
                    </li>

                    <li class="sidebar-main-title">
                        <div>
                            <h6>การจัดการ</h6>
                        </div>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title {{ request()->routeIs('product-categories.*') || request()->routeIs('products.*') ? 'active' : '' }}"
                            href="javascript:void(0)">
                            <i data-feather="package"></i><span>จัดการสินค้า</span>
                        </a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="{{ route('admin.product-categories.index') }}"
                                    class="{{ request()->routeIs('admin.product-categories.*') ? 'active' : '' }}">หมวดหมู่สินค้า</a>
                            </li>
                            <li><a href="#">รายการสินค้า</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="users"></i><span>จัดการลูกค้า</span>
                        </a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="#">รายชื่อลูกค้า</a></li>
                        </ul>
                    </li>
                    
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>