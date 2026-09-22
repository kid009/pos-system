<x-app-layout>
    <x-slot:title>Dashboard</x-slot:title>

    <div class="space-y-6">

        <!-- Page Heading & Generate Report Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200">
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Dashboard</h1>

            <div>
                <a href="#" class="inline-flex items-center justify-center gap-2 px-3.5 py-2 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                    <!-- Download Icon (SVG) -->
                    <svg class="w-4 h-4 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Generate Report</span>
                </a>
            </div>
        </div>

        <!-- Metric Cards Row (4 Columns Grid) -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">

            <!-- 1. Earnings (Monthly) Card -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 border-l-4 border-l-blue-600 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Earnings (Monthly)</p>
                    <p class="text-xl font-bold text-slate-800">$40,000</p>
                </div>
                <!-- Calendar Icon -->
                <div class="text-slate-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <!-- 2. Earnings (Annual) Card -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 border-l-4 border-l-emerald-500 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-600">Earnings (Annual)</p>
                    <p class="text-xl font-bold text-slate-800">$215,000</p>
                </div>
                <!-- Dollar Icon -->
                <div class="text-slate-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- 3. Tasks Card with Progress Bar -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 border-l-4 border-l-cyan-500 shadow-sm flex items-center justify-between">
                <div class="space-y-2 flex-1 pr-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-cyan-600">Tasks</p>
                    <div class="flex items-center gap-3">
                        <span class="text-xl font-bold text-slate-800">50%</span>
                        <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-cyan-500 h-2 rounded-full w-1/2"></div>
                        </div>
                    </div>
                </div>
                <!-- Clipboard List Icon -->
                <div class="text-slate-300 flex-shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
            </div>

            <!-- 4. Pending Requests Card -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 border-l-4 border-l-amber-500 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-600">Pending Requests</p>
                    <p class="text-xl font-bold text-slate-800">18</p>
                </div>
                <!-- Chat Bubble / Comments Icon -->
                <div class="text-slate-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
