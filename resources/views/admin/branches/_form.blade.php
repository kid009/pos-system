<div class="mb-3">
    <label for="tenant_id" class="form-label">Tenant</label>
    <select name="tenant_id" id="tenant_id" class="form-select @error('tenant_id') is-invalid @enderror" >
        <option value="">-- Select Tenant --</option>
        @foreach ($tenants as $tenant)
        <option value="{{ $tenant->id }}" {{ old('tenant_id', $branch->tenant_id ?? '') == $tenant->id ? 'selected' : ''}}>
            {{ $tenant->name }}
        </option>
        @endforeach
    </select>
    @error('tenant_id')
        <sapn class="text-danger">{{ $message }}</sapn>
    @enderror
</div>
<div class="mb-3">
    <label for="name" class="form-label">Branch Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $branch->name ?? '') }}">
    @error('name')
        <sapn class="text-danger">{{ $message }}</sapn>
    @enderror
</div>
<div class="mb-3">
    <label for="address" class="form-label">Address</label>
    <textarea name="address" id="address" class="form-control"
        rows="3">{{ old('address', $branch->address ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label for="phone" class="form-label">Phone</label>
    <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone', $branch->phone ?? '') }}">
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('admin.branches.index') }}" class="btn btn-secondary">Cancel</a>