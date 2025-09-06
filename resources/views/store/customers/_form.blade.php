<div class="mb-3">
    <label for="name" class="form-label">Customer Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $customer->name ?? '') }}">
    @error('name')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone" id="phone"
                value="{{ old('phone', $customer->phone ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="tax_id" class="form-label">Tax ID</label>
            <input type="text" class="form-control" name="tax_id" id="tax_id"
                value="{{ old('tax_id', $customer->tax_id ?? '') }}">
        </div>
    </div>
</div>
<div class="mb-3">
    <label for="address" class="form-label">Address</label>
    <textarea name="address" id="address" class="form-control"
        rows="3">{{ old('address', $customer->address ?? '') }}</textarea>
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('store.customers.index') }}" class="btn btn-secondary">Cancel</a>