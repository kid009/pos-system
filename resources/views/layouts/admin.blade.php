<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel POS') }} - Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 flex h-screen overflow-hidden">

    <aside class="w-64 bg-gray-100 border-r border-gray-200 hidden md:flex flex-col">
        <div class="h-16 flex items-center justify-center border-b border-gray-200 bg-white">
            <h1 class="text-xl font-bold text-blue-500">POS System</h1>
        </div>
        <nav class="flex-1 overflow-y-auto p-4 space-y-2">
            <a href="{{ route('dashboard') }}"
                class="flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-md font-medium">
                Dashboard
            </a>
            <a href="{{ route('product-categories.index') }}"
                class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md font-medium">
                Product Category Manage
            </a>
            <a href="{{ route('product.index') }}"
                class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md font-medium">
                Product Manage
            </a>
            <a href="{{ route('roles.index') }}"
                class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md font-medium">
                Roles Manage
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col h-screen">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ $header ?? 'Dashboard' }}
            </h2>

            <div class="flex items-center space-x-4">
                <div x-data="{ open: false }" class="relative">

                    <button @click="open = !open" @click.outside="open = false"
                        class="flex items-center space-x-2 text-sm text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <span>User: {{ Auth::user()->name ?? 'Guest' }}</span>
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-200 focus:outline-none"
                        style="display: none;">

                        <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition">
                            User Profile
                        </a>

                        <div class="border-t border-gray-100 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="block px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 transition">
                                Logout
                            </a>
                        </form>

                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </main>
    </div>

</body>

</html>
