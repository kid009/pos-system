@props([
    'paginator' => null,
])

<div class="overflow-hidden bg-white rounded-xl border border-slate-200 shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                <tr>
                    {{ $header }}
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if ($paginator && $paginator->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $paginator->links() }}
        </div>
    @endif
</div>
