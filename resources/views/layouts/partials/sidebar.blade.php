<header class="main-nav">
    <div class="text-center sidebar-user">
        <a class="setting-primary" href="#"><i data-feather="settings"></i></a>
        <img class="img-90 rounded-circle" src="{{ asset('assets/images/dashboard/1.png') }}" alt="">
        <a href="#">
            <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->name }}</h6>
        </a>
        <p class="mb-0 font-roboto">{{ Auth::user()->getRoleNames()->first() }}</p>
        {{-- ... Other user info ... --}}
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
                    <li class="sidebar-main-title">
                        <div>
                            <h6>General</h6>
                        </div>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)"><i
                                data-feather="home"></i><span>Dashboard</span></a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="{{ route('dashboard') }}">Default</a></li>
                        </ul>
                    </li>

                    @role('super-admin')
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Admin Management</h6>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="users"></i><span>Roles & Permissions</span>
                        </a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                            <li><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="{{ route('admin.users.index') }}">
                            <i data-feather="users"></i><span>User Management</span>
                        </a>
                    </li>

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Store Management</h6>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="{{ route('admin.tenants.index') }}">
                            <i data-feather="briefcase"></i><span>Tenant Management</span>
                        </a>
                    </li>
                    @endrole

                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>