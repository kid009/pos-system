<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
  class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"></div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
  class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition-transform duration-300 transform bg-gray-900 lg:translate-x-0 lg:static lg:inset-0">

  <div class="flex items-center justify-center h-16 bg-gray-800 shadow-md">
    <span class="text-2xl font-bold text-white">🔥 P-Gas Admin</span>
  </div>

  <nav class="mt-5 px-4 space-y-2">

    <a href="{{ route('dashboard') }}"
      class="flex items-center px-4 py-2 text-gray-200 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : '' }}">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
        </path>
      </svg>
      Dashboard
    </a>

    <a href="#"
      class="flex items-center px-4 py-2 text-gray-400 rounded-md hover:bg-gray-700 hover:text-white transition-colors">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
        </path>
      </svg>
      ขายหน้าร้าน (POS)
    </a>

    <p class="px-4 pt-4 text-xs font-semibold text-gray-500 uppercase">System</p>
    <a href="#" class="flex items-center px-4 py-2 text-gray-400 rounded-md hover:bg-gray-700 hover:text-white">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
        </path>
      </svg>
      Users & Roles
    </a>

  </nav>
</aside>
