<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - ' : '' }}{{ config('app.name', 'Laravel POS') }}</title>

    <!-- System Typography -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Application Asset Pipeline (Tailwind CSS + Vanilla JS via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased text-slate-900 selection:bg-blue-600 selection:text-white">
    <!-- Outer Application Shell (Full Viewport Height) -->
    <div class="flex h-screen overflow-hidden bg-slate-100">

        <!-- 1. Sidebar Container (Included Sub-view) -->
        <div id="sidebarBackdrop" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm hidden lg:hidden transition-opacity"></div>
        <aside id="mainSidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white flex flex-col transition-transform duration-300 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-auto">
            @include('layouts.sidebar')
        </aside>

        <!-- 2. Content Application Column -->
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            <!-- Topbar Navigation -->
            <header class="sticky top-0 z-30 flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8 bg-white border-b border-slate-200 shadow-sm">
                <!-- Mobile Sidebar Toggle Button -->
                <button type="button" id="mobileSidebarBtn" class="p-2 -ml-2 text-slate-500 rounded-lg lg:hidden hover:text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Toggle Sidebar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Navbar Components Hook -->
                @include('layouts.navbar')
            </header>

            <!-- Scrollable Content Area -->
            <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6 focus:outline-none">
                <div class="max-w-7xl mx-auto space-y-6">
                    <!-- Global Session Alerts Integration -->
                    <x-alert />

                    <!-- Dynamic Page Content Insertion -->
                    {{ $slot }}
                </div>
            </main>

            <!-- Sticky Bottom Footer -->
            <footer class="bg-white border-t border-slate-200 py-3 px-4 sm:px-6 text-center text-xs text-slate-500">
                @include('layouts.footer')
            </footer>
        </div>
    </div>

    <!-- 3. Native Vanilla JS Logout Confirmation Modal (Zero jQuery / Bootstrap) -->
    <div id="logoutModal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4 overflow-x-hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm" role="dialog" aria-modal="true">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transition-all transform">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto rounded-full bg-rose-100 text-rose-600 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-center text-slate-900">Ready to End Session?</h3>
                <p class="mt-2 text-sm text-center text-slate-500">
                    Select "Logout" below to securely terminate your administrative POS session.
                </p>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-100">
                <button type="button" id="closeLogoutModalBtn" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    Cancel
                </button>
                <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-rose-600 rounded-lg hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- 4. Scoped Vanilla JS Controller: Sidebar & Modal Operations (Strictly No Alpine.js) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar Mobile Drawer Controller
            const mobileSidebarBtn = document.getElementById('mobileSidebarBtn');
            const mainSidebar = document.getElementById('mainSidebar');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            function toggleSidebar() {
                const isOpen = !mainSidebar.classList.contains('-translate-x-full');
                if (isOpen) {
                    mainSidebar.classList.add('-translate-x-full');
                    sidebarBackdrop.classList.add('hidden');
                } else {
                    mainSidebar.classList.remove('-translate-x-full');
                    sidebarBackdrop.classList.remove('hidden');
                }
            }

            if (mobileSidebarBtn && mainSidebar && sidebarBackdrop) {
                mobileSidebarBtn.addEventListener('click', toggleSidebar);
                sidebarBackdrop.addEventListener('click', toggleSidebar);
            }

            // Global Logout Modal Controller (Vanilla JS)
            const logoutModal = document.getElementById('logoutModal');
            const closeLogoutModalBtn = document.getElementById('closeLogoutModalBtn');
            const triggerLogoutBtns = document.querySelectorAll('[data-trigger-logout]');

            function openLogoutModal() {
                if (logoutModal) logoutModal.classList.remove('hidden');
            }

            function closeLogoutModal() {
                if (logoutModal) logoutModal.classList.add('hidden');
            }

            triggerLogoutBtns.forEach(btn => btn.addEventListener('click', openLogoutModal));
            if (closeLogoutModalBtn) closeLogoutModalBtn.addEventListener('click', closeLogoutModal);

            // Close modal when pressing ESC key
            window.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && logoutModal && !logoutModal.classList.contains('hidden')) {
                    closeLogoutModal();
                }
            });
        });
    </script>

    <!-- Page Specific Injected Scripts Slot -->
    {{ $scripts ?? '' }}
</body>
</html>
