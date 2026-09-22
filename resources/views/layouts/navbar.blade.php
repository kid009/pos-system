<div class="flex items-center justify-end w-full space-x-3">

    <!-- 1. Nav Item: Search Dropdown (Visible Only on Mobile - XS) -->
    <div class="relative sm:hidden" id="searchDropdownWrapper">
        <button type="button"
                id="searchDropdownBtn"
                class="p-2 text-slate-500 rounded-lg hover:text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                aria-expanded="false"
                aria-label="Search">
            <!-- Search Icon (fas fa-search) -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>

        <!-- Dropdown - Mobile Search Form -->
        <div id="searchDropdownMenu"
             class="hidden absolute right-0 mt-2 w-72 p-3 bg-white rounded-xl shadow-xl border border-slate-100 z-50 transition-all">
            <form class="w-full">
                <div class="flex items-center rounded-lg border border-slate-300 overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent">
                    <input type="text"
                           class="w-full px-3 py-1.5 text-xs text-slate-800 bg-slate-50 focus:outline-none"
                           placeholder="Search for..."
                           aria-label="Search">
                    <button type="button"
                            class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. Topbar Divider (Hidden on XS, Visible on SM up) -->
    <div class="hidden sm:block h-8 w-px bg-slate-200 mx-1"></div>

    <!-- 3. Nav Item: User Information -->
    <div class="relative" id="userDropdownWrapper">
        <button type="button"
                id="userDropdownBtn"
                class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                aria-expanded="false">
            <!-- User Icon (fas fa-user-circle) -->
            <svg class="w-7 h-7 text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
            </svg>
            <span class="hidden lg:inline text-xs font-semibold text-slate-600">
                {{ Auth::user()?->name ?? 'User' }}
            </span>
        </button>

        <!-- Dropdown - User Information Menu -->
        <div id="userDropdownMenu"
             class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-1.5 text-xs text-slate-700 z-50 transition-all">

            <!-- Profile -->
            <a href="#" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Profile</span>
            </a>

            <!-- Settings -->
            <a href="#" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Settings</span>
            </a>

            <!-- Activity Log -->
            <a href="#" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Activity Log</span>
            </a>

            <!-- Divider -->
            <div class="my-1 border-t border-slate-100"></div>

            <!-- Logout -->
            <button type="button"
                    data-trigger-logout
                    class="w-full flex items-center gap-2.5 px-4 py-2 text-rose-600 hover:bg-rose-50/60 transition-colors text-left">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="font-medium">Logout</span>
            </button>
        </div>
    </div>

</div>

<!-- Vanilla JS Controller for Navbar Dropdowns (Strictly No Alpine.js) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchWrapper = document.getElementById('searchDropdownWrapper');
        const searchBtn = document.getElementById('searchDropdownBtn');
        const searchMenu = document.getElementById('searchDropdownMenu');

        const userWrapper = document.getElementById('userDropdownWrapper');
        const userBtn = document.getElementById('userDropdownBtn');
        const userMenu = document.getElementById('userDropdownMenu');

        // Toggle Search Dropdown
        if (searchBtn && searchMenu) {
            searchBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                if (userMenu) userMenu.classList.add('hidden'); // ปิดเมนู User ถ้าเปิดค้างอยู่
                searchMenu.classList.toggle('hidden');
            });
        }

        // Toggle User Dropdown
        if (userBtn && userMenu) {
            userBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                if (searchMenu) searchMenu.classList.add('hidden'); // ปิดเมนู Search ถ้าเปิดค้างอยู่
                userMenu.classList.toggle('hidden');
            });
        }

        // ปิด Dropdown เมื่อคลิกนอกพื้นที่ (Click Outside Handler)
        document.addEventListener('click', function (e) {
            if (searchWrapper && !searchWrapper.contains(e.target)) {
                searchMenu?.classList.add('hidden');
            }
            if (userWrapper && !userWrapper.contains(e.target)) {
                userMenu?.classList.add('hidden');
            }
        });

        // ปิด Dropdown เมื่อกดปุ่ม Escape (Accessibility)
        window.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                searchMenu?.classList.add('hidden');
                userMenu?.classList.add('hidden');
            }
        });
    });
</script>
