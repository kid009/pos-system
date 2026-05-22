<div class="space-y-5">

    {{-- Name Field --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            Product Category Name <span class="text-rose-500">*</span>
        </label>
        <input type="text" name="name" id="name"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-rose-500 focus:border-rose-500 focus:ring-rose-500 @enderror"
            value="{{ old('name', $productCategory->name ?? '') }}" required autofocus>
        @error('name')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Active Toggle --}}
    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
        <label class="flex items-center gap-3 cursor-pointer">
            <div class="relative">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="sr-only peer"
                    @checked(old('is_active', isset($productCategory) ? $productCategory->is_active : true))>
                <div
                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500">
                </div>
            </div>
            <div>
                <span class="block text-sm font-semibold text-gray-900">Active</span>
                <span class="block text-sm text-gray-500 mt-0.5">Enabled categories will be displayed as filter buttons
                    on the POS page</span>
            </div>
        </label>
    </div>

</div>
