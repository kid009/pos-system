<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" >
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="sku" class="form-label">SKU</label>
            <input type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" id="sku" value="{{ old('sku', $product->sku ?? '') }}">
            @error('sku')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="product_category_id" class="form-label">Category</label>
    <select name="product_category_id" id="product_category_id" class="form-select @error('product_category_id') is-invalid @enderror" >
        <option value="">-- Select Category --</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ old('product_category_id', $product->product_category_id ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('product_category_id')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

{{-- START: ส่วนที่เพิ่มเข้ามา --}}
@if (isset($product) && $product->image)
    <div class="mb-3">
        <label class="form-label">Current Image</label>
        <div>
            <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" style="height: 60px; width: 60px;" class="img-thumbnail">
        </div>
    </div>
@endif
{{-- END: ส่วนที่เพิ่มเข้ามา --}}

<div class="mb-3">
    <label for="image" class="form-label">Product Image</label>
    <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image">
    @error('image')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="cost" class="form-label">Cost Price</label>
            <input type="number" step="0.01" class="form-control @error('cost') is-invalid @enderror" name="cost" id="cost" value="{{ old('cost', $product->cost ?? '0.00') }}">
            @error('cost')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="price" class="form-label">Selling Price</label>
            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" name="price" id="price" value="{{ old('price', $product->price ?? '0.00') }}" >
            @error('price')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('store.products.index') }}" class="btn btn-secondary">Cancel</a>