<header class="main-nav">
    <div class="text-center sidebar-user">
        <a class="setting-primary" href="#"><i data-feather="settings"></i></a>
        <img class="img-90 rounded-circle" src="{{ asset('assets/images/dashboard/1.png') }}" alt="">
        <a href="#">
            {{-- แสดงชื่อ User ที่ Login อยู่ --}}
            <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->name }}</h6>
        </a>
        {{-- แสดง Role แรกที่ User มี --}}
        <p class="mb-0 font-roboto">{{ ucfirst(Auth::user()->getRoleNames()->first()) }}</p>
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

                    @foreach ($menus as $menu)
                    @if($menu->is_title)
                    <li class="sidebar-main-title">
                        <div>
                            <h6>{{ $menu->name }}</h6>
                        </div>
                    </li>
                    @else
                    <li class="dropdown">
                        {{-- ตรวจสอบว่ามีเมนูย่อยหรือไม่ --}}
                        @if($menu->children->isNotEmpty())
                        <a class="nav-link menu-title {{ $menu->children->contains(fn($child) => request()->routeIs($child->route_name)) ? 'active' : '' }}"
                            href="javascript:void(0)">
                            <i data-feather="{{ $menu->icon }}"></i><span>{{ $menu->name }}</span>
                        </a>
                        <ul class="nav-submenu menu-content">
                            @foreach($menu->children as $child)
                            <li><a href="{{ $child->route_name ? route($child->route_name) : '#' }}">{{ $child->name
                                    }}</a></li>
                            @endforeach
                        </ul>
                        @else
                        <a class="nav-link menu-title link-nav {{ request()->routeIs($menu->route_name) ? 'active' : '' }}"
                            href="{{ $menu->route_name ? route($menu->route_name) : '#' }}">
                            <i data-feather="{{ $menu->icon }}"></i><span>{{ $menu->name }}</span>
                        </a>
                        @endif
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>