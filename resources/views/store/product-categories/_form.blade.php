<div class="mb-3">
    <label for="product_main_category_id" class="form-label">Main Category</label>
    <select name="product_main_category_id" id="product_main_category_id" class="form-select @error('product_main_category_id') is-invalid @enderror" >
        <option value="">-- Select Main Category --</option>
        @foreach ($mainCategories as $mainCategory)
        <option value="{{ $mainCategory->id }}" 
            {{ old('product_main_category_id', $productCategory->product_main_category_id ?? '') == $mainCategory->id ? 'selected' : '' }}>
            {{ $mainCategory->name }}
        </option>
        @endforeach
    </select>
    @error('product_main_category_id')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
<div class="mb-3">
    <label for="name" class="form-label">Sub Category Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"
        value="{{ old('name', $productCategory->name ?? '') }}" >
    @error('name')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('store.product-categories.index') }}" class="btn btn-secondary">Cancel</a>