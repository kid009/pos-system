@props(['variant' => 'primary'])

@php
    $baseClasses =
        'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-sm transition ease-in-out duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2';

    $variants = [
        'primary' => 'bg-blue-500 text-white hover:bg-blue-700 focus:ring-blue-500',
        'success' => 'bg-emerald-500 text-white hover:bg-emerald-700 focus:ring-emerald-500',
        'danger' => 'bg-rose-500 text-white hover:bg-rose-700 focus:ring-rose-500',
        'warning' => 'bg-amber-500 text-white hover:bg-amber-600 focus:ring-amber-500',
        'secondary' => 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 focus:ring-gray-500',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
