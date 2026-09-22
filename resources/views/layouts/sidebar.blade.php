<div class="flex flex-col h-full bg-slate-900 border-r border-slate-800 text-slate-300 select-none">

    <!-- Sidebar - Brand -->
    <a href="{{ url('/') }}" class="flex items-center justify-center h-16 px-4 bg-slate-950/40 border-b border-slate-800/80 gap-3 group">
        <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/30 transform -rotate-12 transition-transform group-hover:rotate-0">
            <!-- Flame Icon (fas fa-fire replacement) -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
            </svg>
        </div>
        <span class="text-base font-bold tracking-tight text-white">PGAS-SHOP</span>
    </a>

    <!-- Divider -->
    <div class="border-t border-slate-800/80"></div>

    <!-- Nav Items Container -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

        <!-- Nav Item - Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                  {{ request()->routeIs('dashboard')
                      ? 'bg-blue-600 text-white shadow-sm'
                      : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
            <!-- Circle Bullet Icon (fas fa-circle replacement) -->
            <svg class="w-2.5 h-2.5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500' }}" fill="currentColor" viewBox="0 0 8 8">
                <circle cx="4" cy="4" r="3" />
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Nav Item - Product Categories -->
        <a href="{{ route('product-categories.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                  {{ request()->routeIs('product-categories.*')
                      ? 'bg-blue-600 text-white shadow-sm'
                      : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
            <svg class="w-2.5 h-2.5 flex-shrink-0 {{ request()->routeIs('product-categories.*') ? 'text-white' : 'text-slate-500' }}" fill="currentColor" viewBox="0 0 8 8">
                <circle cx="4" cy="4" r="3" />
            </svg>
            <span>Product Categories</span>
        </a>

        <!-- Nav Item - Products -->
        <a href="{{ route('products.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                  {{ request()->routeIs('products.*')
                      ? 'bg-blue-600 text-white shadow-sm'
                      : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
            <svg class="w-2.5 h-2.5 flex-shrink-0 {{ request()->routeIs('products.*') ? 'text-white' : 'text-slate-500' }}" fill="currentColor" viewBox="0 0 8 8">
                <circle cx="4" cy="4" r="3" />
            </svg>
            <span>Products</span>
        </a>

    </nav>

</div>
