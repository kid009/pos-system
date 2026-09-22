@props([
    'name',
    'label' => null,
    'required' => false,
    'hint' => null,
    'rows' => 3,
])

@php
    $hasError = $errors->has($name);
    $textareaClasses = 'block w-full px-3.5 py-2 text-sm rounded-lg border shadow-sm transition-colors duration-150 focus:outline-none focus:ring-2 focus:border-transparent ' .
        ($hasError
            ? 'border-rose-300 bg-rose-50/30 text-rose-900 focus:ring-rose-500'
            : 'border-slate-300 bg-white text-slate-900 focus:ring-blue-500 focus:border-blue-500');
@endphp

@if($label)
    <x-form.group :name="$name" :label="$label" :required="$required" :hint="$hint">
        <textarea name="{{ $name }}"
                  id="{{ $name }}"
                  rows="{{ $rows }}"
                  {{ $required ? 'required' : '' }}
                  {{ $attributes->merge(['class' => $textareaClasses]) }}>{{ old($name, $slot->isNotEmpty() ? trim($slot) : '') }}</textarea>
    </x-form.group>
@else
    <textarea name="{{ $name }}"
              id="{{ $name }}"
              rows="{{ $rows }}"
              {{ $required ? 'required' : '' }}
              {{ $attributes->merge(['class' => $textareaClasses]) }}>{{ old($name, $slot->isNotEmpty() ? trim($slot) : '') }}</textarea>
@endif
