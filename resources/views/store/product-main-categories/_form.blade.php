<div class="mb-3">
    <label for="name" class="form-label">Main Category Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"
        value="{{ old('name', $productMainCategory->name ?? '') }}">
    @error('name')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('store.product-main-categories.index') }}" class="btn btn-secondary">Cancel</a>