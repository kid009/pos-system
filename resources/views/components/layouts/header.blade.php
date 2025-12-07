<header class="flex items-center justify-between px-6 py-4 bg-white border-b-4 border-indigo-600">
  <div class="flex items-center">
    <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" />
      </svg>
    </button>

    <h1 class="ml-4 text-2xl font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
  </div>

  <div class="flex items-center gap-4">
    <div class="relative" x-data="{ open: false }">
      <button @click="open = !open" class="flex items-center text-gray-600 focus:outline-none hover:text-gray-800">
        <span class="mr-2">{{ auth()->user()->name }}</span>
        <img class="object-cover w-8 h-8 rounded-full"
          src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=random" alt="User avatar">
      </button>

      <div x-show="open" @click.away="open = false"
        class="absolute right-0 z-10 w-48 mt-2 overflow-hidden bg-white rounded-md shadow-xl"
        style="display: none;">
        <a href="{{ route('profile.edit') }}"
          class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Profile</a>

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">
            Logout
          </button>
        </form>
      </div>
    </div>
  </div>
</header>
