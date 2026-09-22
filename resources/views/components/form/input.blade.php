@props([
    'name',
    'label' => null,
    'type' => 'text',
    'required' => false,
    'hint' => null,
])

@php
    $hasError = $errors->has($name);
    $inputClasses = 'block w-full px-3.5 py-2.5 text-sm rounded-lg border shadow-sm transition-colors duration-150 focus:outline-none focus:ring-2 focus:border-transparent ' .
        ($hasError
            ? 'border-rose-300 bg-rose-50/30 text-rose-900 focus:ring-rose-500'
            : 'border-slate-300 bg-white text-slate-900 focus:ring-blue-500 focus:border-blue-500');
@endphp

@if($label)
    <x-form.group :name="$name" :label="$label" :required="$required" :hint="$hint">
        <input type="{{ $type }}"
               name="{{ $name }}"
               id="{{ $name }}"
               value="{{ old($name, $slot->isNotEmpty() ? trim($slot) : '') }}"
               {{ $required ? 'required' : '' }}
               {{ $attributes->merge(['class' => $inputClasses]) }}>
    </x-form.group>
@else
    <input type="{{ $type }}"
           name="{{ $name }}"
           id="{{ $name }}"
           value="{{ old($name, $slot->isNotEmpty() ? trim($slot) : '') }}"
           {{ $required ? 'required' : '' }}
           {{ $attributes->merge(['class' => $inputClasses]) }}>
@endif
