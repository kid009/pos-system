<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'P-Gas POS' }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>

<body class="bg-base-200 font-sans antialiased">

  <div class="drawer lg:drawer-open">

    <input id="my-drawer" type="checkbox" class="drawer-toggle" />

    <div class="drawer-content flex flex-col min-h-screen">

      <livewire:layout.header :title="$title ?? ''" />

      <main class="flex-1 p-6">
        {{ $slot }}
      </main>
    </div>

    <div class="drawer-side z-50">
      <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>

      <livewire:layout.sidebar />
    </div>
  </div>

  @livewireScripts
</body>

</html>
