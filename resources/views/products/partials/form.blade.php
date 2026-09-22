<x-form.group name="product_category_id" label="Category" :required="true">
    <select name="product_category_id" id="product_category_id"
            class="block w-full px-3.5 py-2.5 text-sm rounded-lg border shadow-sm transition-colors duration-150 focus:outline-none focus:ring-2 focus:border-transparent {{ $errors->has('product_category_id') ? 'border-rose-300 bg-rose-50/30 text-rose-900 focus:ring-rose-500' : 'border-slate-300 bg-white text-slate-900 focus:ring-blue-500 focus:border-blue-500' }}"
            required>
        <option value="">Select Category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ old('product_category_id', $product->product_category_id ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</x-form.group>

<x-form.input name="name" label="Name" :required="true">
    {{ old('name', $product->name ?? '') }}
</x-form.input>

<x-form.textarea name="description" label="Description" rows="4">
    {{ old('description', $product->description ?? '') }}
</x-form.textarea>

<x-form.input name="price" label="Price" type="number" step="0.01" min="0" :required="true">
    {{ old('price', $product->price ?? '') }}
</x-form.input>

<x-form.input name="link_affiliate" label="Affiliate Link" type="url">
    {{ old('link_affiliate', $product->link_affiliate ?? '') }}
</x-form.input>

<x-form.group name="image" label="Cover Image" :required="! isset($product)">
    @if(isset($product) && $product->image)
        <div class="mb-3">
            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-32 w-auto rounded-lg border border-slate-200 object-cover">
            <p class="text-[11px] text-slate-500 mt-1">Current cover image. Upload a new one to replace it.</p>
        </div>
    @endif
    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/webp"
           class="block w-full text-sm text-slate-900 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 {{ $errors->has('image') ? 'file:bg-rose-50 file:text-rose-700' : '' }}">
    <p class="text-[11px] text-slate-500 mt-1">Accepted: JPG, PNG, WEBP. Max size: 2MB.</p>
</x-form.group>

<x-form.toggle name="is_active" label="Active"
               :checked="(bool) old('is_active', $product->is_active ?? true)" />
