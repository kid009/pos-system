@csrf
<div class="mb-3">
    <label for="permission_name" class="form-label">Permission Name</label>
    <input type="text" class="form-control @error('permission_name') is-invalid @enderror" id="permission_name" name="permission_name"
        value="{{ old('name', $permission->name ?? '') }}">
    @error('permission_name')
    <span class="text-danger">{{ $message }}</span>
    @enderror
    <div class="form-text">Use resource.action format (e.g., product.view, user.delete)</div>
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Cancel</a>