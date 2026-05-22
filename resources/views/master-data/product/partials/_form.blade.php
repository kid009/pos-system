<div class="space-y-5">
    {{-- SKU Field --}}
    <div>
        <label for="sku" class="block text-sm font-medium text-gray-700">SKU <span
                class="text-rose-500">*</span></label>
        <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('sku')
            <span class="text-rose-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    {{-- Product Name Field --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Product Name <span
                class="text-rose-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        @error('name')
            <span class="text-rose-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    {{-- Category Field --}}
    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
        <select name="category_id" id="category_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">-- Select Category --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <span class="text-rose-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    {{-- Price Field --}}
    <div>
        <label for="price" class="block text-sm font-medium text-gray-700">Selling Price <span
                class="text-rose-500">*</span></label>
        <div class="relative mt-1 rounded-md shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <span class="text-gray-500 sm:text-sm">฿</span>
            </div>
            <input type="number" step="0.01" name="price" id="price"
                value="{{ old('price', isset($product) ? $product->price : '0.00') }}"
                class="block w-full rounded-md border-gray-300 pl-7 focus:border-blue-500 focus:ring-blue-500">
        </div>
        @error('price')
            <span class="text-rose-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    {{-- Description Field --}}
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" id="description" rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <span class="text-rose-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    {{-- Active Toggle Switch (Tailwind CSS Only) --}}
    <div class="pt-2">
        <label class="relative inline-flex items-center cursor-pointer">
            {{-- Hidden input ป้องกันกรณีไม่เปิดสวิตช์ จะได้ส่งค่า 0 ไปให้ Backend เสมอ --}}
            <input type="hidden" name="is_active" value="0">

            {{-- ค่า Default: ถ้ามีการแก้ให้ดูค่าเดิม ถ้าสร้างใหม่ให้เปิด (true) ไว้ก่อน --}}
            <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>

            <div
                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500">
            </div>

            <span class="ml-3 text-sm font-medium text-gray-900">Active (Visible in POS)</span>
        </label>
    </div>
</div>
