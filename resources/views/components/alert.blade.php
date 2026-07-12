@props([
    'type' => 'success',
    'message' => '',
    'dismissAfter' => 5000
])

@php
    $styles = [
        'success' => 'text-fg-success-strong rounded-base bg-success-soft',
        'error' => 'text-fg-danger-strong rounded-base bg-danger-soft',
        'warning' => 'text-fg-warning rounded-base bg-warning-soft',
        'info' => 'text-heading rounded-base bg-neutral-secondary-medium',
    ];

    $classes = $styles[$type] ?? $styles['success'];
@endphp

@if ($message)
    <div
        x-data="{ show: true }"
        x-init="setTimeout( () => show = false, {{$dismissAfter}})"
        x-show="show"
        x-transition:leave="transition ease-out duration-500"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="p-4 mt-4 text-sm {{$classes}}"
        role="alert"
    >
        <span class="font-medium">{{$message}}</span>
    </div>
@endif
