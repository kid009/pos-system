@props(['status' => 'default'])

@php
    $baseClasses = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full';

    $variants = [
        'success' => 'bg-emerald-100 text-emerald-700', // มีสต็อก, สำเร็จ
        'warning' => 'bg-amber-100 text-amber-700', // สต็อกต่ำ, รอดำเนินการ
        'danger' => 'bg-rose-100 text-rose-700', // หมดสต็อก, ยกเลิก
        'default' => 'bg-gray-100 text-gray-700', // ระงับ, ทั่วไป
    ];

    $classes = $baseClasses . ' ' . ($variants[$status] ?? $variants['default']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
