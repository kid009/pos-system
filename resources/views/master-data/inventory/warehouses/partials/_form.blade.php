<div class="space-y-5">
    {{-- Warehouse Code --}}
    <div>
        <label for="code" class="block text-sm font-medium text-gray-700">Warehouse Code <span
                class="text-rose-500">*</span></label>
        <input type="text" name="code" id="code" value="{{ old('code', $warehouse->code ?? '') }}"
            placeholder="e.g. WH-BKK-01"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
            required>
        <p class="text-xs text-gray-400 mt-1">Alphanumeric, dashes, and underscores only. Auto-converted to uppercase.
        </p>
        @error('code')
            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    {{-- Warehouse Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Warehouse Name <span
                class="text-rose-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $warehouse->name ?? '') }}"
            placeholder="e.g. Bangkok Central Distribution"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
            required>
        @error('name')
            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    {{-- Active Toggle Switch (Tailwind CSS Only) --}}
    <div class="pt-2">
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                {{ old('is_active', $warehouse->is_active ?? true) ? 'checked' : '' }}>
            <div
                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500">
            </div>
            <span class="ml-3 text-sm font-medium text-gray-900">Operational Active Status</span>
        </label>
    </div>
</div>
