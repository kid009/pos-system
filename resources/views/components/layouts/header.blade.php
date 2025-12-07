<header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 shadow-sm">

  <div class="flex items-center">
    <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6H20M4 12H20M4 18H11" />
      </svg>
    </button>
    <h2 class="ml-4 text-xl font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h2>
  </div>

  <div x-data="{ open: false }" class="relative">

    <button @click="open = !open"
      class="flex items-center focus:outline-none hover:bg-gray-50 p-2 rounded-lg transition">
      <span class="mr-3 text-sm font-medium text-gray-600 hidden md:block">
        {{ Auth::user()->name ?? 'User' }}
      </span>
      <img class="w-8 h-8 rounded-full bg-gray-300 border border-gray-200"
        src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'U' }}&background=random"
        alt="Avatar">

      <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
      </svg>
    </button>

    <div x-show="open"
      @click.away="open = false"
      x-transition:enter="transition ease-out duration-100"
      x-transition:enter-start="transform opacity-0 scale-95"
      x-transition:enter-end="transform opacity-100 scale-100"
      x-transition:leave="transition ease-in duration-75"
      x-transition:leave-start="transform opacity-100 scale-100"
      x-transition:leave-end="transform opacity-0 scale-95"
      style="display: none;"
      class="absolute right-0 z-50 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 focus:outline-none">

      <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
        ข้อมูลส่วนตัว
      </a>

      <div class="border-t border-gray-100"></div>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">
          ออกจากระบบ
        </button>
      </form>

    </div>
  </div>
</header>
