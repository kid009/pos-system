<div class="page-main-header">
    <div class="m-0 main-header-right row">
        <div class="main-header-left">
            <div class="logo-wrapper"><a href="index.html"><img class="img-fluid"
                        src="{{ asset('admin_assets/images/logo/icon-logo.png') }}" alt=""></a></div>
            <div class="dark-logo-wrapper"><a href="index.html"><img class="img-fluid"
                        src="{{ asset('admin_assets/images/logo/dark-logo.png') }}" alt=""></a></div>
            <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center"
                    id="sidebar-toggle"></i></div>
        </div>
        <div class="left-menu-header col"></div>
        <div class="p-0 nav-right col pull-right right-menu">
            <ul class="nav-menus">
                <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i
                            data-feather="maximize"></i></a></li>
                <li>
                    <div class="mode"><i class="fa fa-moon-o"></i></div>
                </li>
                <li class="p-0 onhover-dropdown">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a class="btn btn-primary-light" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i data-feather="log-out"></i>Log out
                        </a>
                    </form>
                </li>
            </ul>
        </div>
        <div class="w-auto d-lg-none mobile-toggle pull-right"><i data-feather="more-horizontal"></i></div>
    </div>
</div>