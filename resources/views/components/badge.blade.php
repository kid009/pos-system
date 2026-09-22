@props(['variant' => 'default'])

@php
    $classes = match($variant) {
        'success', 'active', 'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'danger', 'inactive', 'failed', 'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
        'warning', 'pending', 'hold' => 'bg-amber-50 text-amber-700 border-amber-200',
        'info', 'processing' => 'bg-blue-50 text-blue-700 border-blue-200',
        default => 'bg-slate-100 text-slate-600 border-slate-200',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {$classes}"]) }}>
    {{ $slot }}
</span>
