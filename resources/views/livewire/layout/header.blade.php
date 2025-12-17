<div class="navbar bg-base-100 border-b border-base-200 sticky top-0 z-30">

  <div class="flex-1">

    <label for="my-drawer" class="btn btn-square btn-ghost lg:hidden mr-2">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
        class="inline-block w-6 h-6 stroke-current">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </label>

    <h1 class="text-xl font-bold text-base-content px-2">
      {{ $title ?? 'Dashboard' }}
    </h1>
  </div>

  <div class="flex-none gap-2">

    <div class="hidden md:block text-sm font-medium text-base-content/70 mr-2">
      {{ auth()->user()->name }}
    </div>

    <div class="dropdown dropdown-end">

      <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar border border-base-300">
        <div class="w-10 rounded-full">
          <img alt="User Avatar"
            src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=random" />
        </div>
      </div>

      <ul tabindex="0"
        class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52 border border-base-200">
        <li>
          <a href="{{ route('profile') }}" wire:navigate class="justify-between">
            ข้อมูลส่วนตัว
            <span class="badge badge-primary">New</span>
          </a>
        </li>
        <li>
          <button wire:click="logout" class="text-error font-medium">
            ออกจากระบบ
          </button>
        </li>
      </ul>

    </div>
  </div>
</div>
