<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'P-Gas POS' }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">

  <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

    @include('components.layouts.sidebar')

    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

      @include('components.layouts.header')

      <main class="w-full grow p-6">
        {{ $slot }}
      </main>
    </div>

  </div>
</body>

</html>
