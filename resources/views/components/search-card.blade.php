@props([
    'action', // URL ปลายทาง เช่น route('products.index')
    'resetUrl' => null, // URL สำหรับล้างค่า เช่น route('products.index')
])

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <!-- Header / Toggle Header -->
    <div class="px-6 py-3.5 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-2 text-slate-700">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-600">Search & Filters</span>
        </div>

        @if(request()->hasAny(array_keys(request()->query())) && !request()->only('page'))
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-100">
                Filters Applied
            </span>
        @endif
    </div>

    <!-- Search Form Form Body -->
    <form method="GET" action="{{ $action }}" class="p-6 search-filter-form" novalidate>
        <!-- Grid ช่องค้นหา (ส่งผ่าน Slot) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{ $slot }}
        </div>

        <!-- Action Buttons Bar -->
        <div class="mt-5 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-2.5">
            @if($resetUrl)
                <a href="{{ $resetUrl }}"
                   class="w-full sm:w-auto px-4 py-2 text-center text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                    Clear Filters
                </a>
            @endif

            <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>Filter Records</span>
            </button>
        </div>
    </form>
</div>

<!-- Scoped Vanilla JS: URL Cleaner (ป้องกันส่ง Parameter ว่างขึ้น URL) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('.search-filter-form');

        forms.forEach(function (form) {
            form.addEventListener('submit', function () {
                const inputs = form.querySelectorAll('input, select');
                inputs.forEach(function (input) {
                    // หากไม่ได้กรอกค่า ให้ Disable ทันทีเพื่อไม่ให้เบราว์เซอร์แนบค่าว่างขึ้น URL
                    if (!input.value || input.value.trim() === '') {
                        input.disabled = true;
                    }
                });
            });
        });
    });
</script>
