@props([
    'name' => 'is_active',
    'label' => 'Operational Active Status',
    'description' => null,
    'checked' => true,
])

<div class="pt-2 border-t border-slate-100">
    <div class="flex items-center justify-between">
        <div>
            <span class="block text-sm font-semibold text-slate-900">{{ $label }}</span>
            @if($description)
                <span class="block text-xs text-slate-500 mt-0.5">{{ $description }}</span>
            @endif
        </div>

        <label class="relative inline-flex items-center cursor-pointer select-none flex-shrink-0">
            <input type="hidden" name="{{ $name }}" value="0">
            <input type="checkbox"
                   name="{{ $name }}"
                   id="{{ $name }}"
                   value="1"
                   class="sr-only peer"
                   {{ (bool) old($name, $checked) ? 'checked' : '' }}>
            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
        </label>
    </div>

    @error($name)
        <p class="text-xs font-medium text-rose-600 mt-2 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
