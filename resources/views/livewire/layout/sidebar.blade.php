<aside class="menu p-4 w-64 min-h-full bg-base-100 text-base-content border-r border-base-300">

  <div class="flex items-center gap-2 px-2 mb-8 text-xl font-bold text-primary">
    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">...</svg> P-Gas POS
  </div>

  <ul class="space-y-2">
    @foreach ($menus as $menu)
      @can($menu->permission_name)
        <li>
          <a href="{{ Route::has($menu->route) ? route($menu->route) : '#' }}"
            wire:navigate
            class="{{ request()->routeIs($menu->route) ? 'active' : '' }}">

            {!! $menu->icon !!}
            {{ $menu->name }}
          </a>
        </li>
      @endcan
    @endforeach
  </ul>

</aside>
