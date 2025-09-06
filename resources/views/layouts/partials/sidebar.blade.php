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
                <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
              </li>
              <li class="sidebar-main-title">
                <div>
                  <h6>General</h6>
                </div>
              </li>
              <li class="dropdown">
                  <a class="nav-link menu-title link-nav {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                      <i data-feather="home"></i><span>Dashboard</span>
                  </a>
              </li>

              {{-- =================================================== --}}
              {{-- ============== Super Admin Section ============== --}}
              {{-- =================================================== --}}
              @role('super-admin')
              <li class="sidebar-main-title">
                <div>
                    <h6>Admin Management</h6>
                </div>
              </li>
              <li class="dropdown">
                  <a class="nav-link menu-title {{ request()->routeIs('admin.roles.*', 'admin.permissions.*') ? 'active' : '' }}" href="javascript:void(0)">
                      <i data-feather="shield"></i><span>Roles & Permissions</span>
                  </a>
                  <ul class="nav-submenu menu-content">
                      <li><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                      <li><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                  </ul>
              </li>
              <li class="dropdown">
                  <a class="nav-link menu-title link-nav {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                      <i data-feather="users"></i><span>User Management</span>
                  </a>
              </li>
              <li class="sidebar-main-title">
                <div>
                    <h6>Store Setup (Admin)</h6>
                </div>
              </li>
              <li class="dropdown">
                  <a class="nav-link menu-title link-nav {{ request()->routeIs('admin.tenants.*') ? 'active' : '' }}" href="{{ route('admin.tenants.index') }}">
                      <i data-feather="briefcase"></i><span>Tenant Management</span>
                  </a>
              </li>
              <li class="dropdown">
                  <a class="nav-link menu-title link-nav {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}" href="{{ route('admin.branches.index') }}">
                      <i data-feather="git-branch"></i><span>Branch Management</span>
                  </a>
              </li>
              @endrole


              {{-- =================================================== --}}
              {{-- =========== Store Management Section ============ --}}
              {{-- =================================================== --}}
              @hasanyrole('super-admin|branch-manager')
              <li class="sidebar-main-title">
                <div>
                    <h6>Store Operations</h6>
                </div>
              </li>
              <li class="dropdown">
                  <a class="nav-link menu-title {{ request()->routeIs('store.product-main-categories.*', 'store.product-categories.*', 'store.products.*') ? 'active' : '' }}" href="javascript:void(0)">
                      <i data-feather="package"></i><span>Product Catalog</span>
                  </a>
                  <ul class="nav-submenu menu-content">
                      <li><a href="{{ route('store.product-main-categories.index') }}">Main Categories</a></li>
                      <li><a href="{{ route('store.product-categories.index') }}">Sub Categories</a></li>
                      <li><a href="{{ route('store.products.index') }}">Products</a></li>
                  </ul>
              </li>
              <li class="dropdown">
                  <a class="nav-link menu-title link-nav {{ request()->routeIs('store.customers.*') ? 'active' : '' }}" href="{{ route('store.customers.index') }}">
                      <i data-feather="users"></i><span>Customer Management</span>
                  </a>
              </li>
              <li class="dropdown">
                  <a class="nav-link menu-title {{ request()->routeIs('store.purchases.*', 'store.stock.*') ? 'active' : '' }}" href="javascript:void(0)">
                      <i data-feather="archive"></i><span>Inventory Mngm.</span>
                  </a>
                  <ul class="nav-submenu menu-content">
                      <li><a href="{{ route('store.purchases.index') }}">Stock In (Purchase)</a></li>
                      <li><a href="{{ route('store.stock.adjustment.create') }}">Stock Adjustment</a></li>
                  </ul>
              </li>
              <li class="dropdown">
                  <a class="nav-link menu-title {{ request()->routeIs('store.expense-categories.*', 'store.expenses.*') ? 'active' : '' }}" href="javascript:void(0)">
                      <i data-feather="dollar-sign"></i><span>Expense Management</span>
                  </a>
                  <ul class="nav-submenu menu-content">
                      <li><a href="{{ route('store.expense-categories.index') }}">Expense Categories</a></li>
                      <li><a href="{{ route('store.expenses.index') }}">Expenses</a></li> 
                  </ul>
              </li>
              @endhasanyrole

            </ul>
          </div>
          <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>