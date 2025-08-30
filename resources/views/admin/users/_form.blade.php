<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name', $user->name ?? '') }}" required>
    @error('name')<span class='text-danger'>{{ $message }}</span>@enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
        value="{{ old('email', $user->email ?? '') }}" required>
    @error('email')<span class='text-danger'>{{ $message }}</span>@enderror
</div>

<div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" {{
        !isset($user) ? 'required' : '' }}>
    @error('password')<span class='text-danger'>{{ $message }}</span>@enderror
    @if (isset($user))
    <div class="form-text">Leave blank to keep the current password.</div>
    @endif
</div>

<div class="mb-3">
    <label for="password_confirmation" class="form-label">Confirm Password</label>
    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" {{ !isset($user)
        ? 'required' : '' }}>
</div>

<div class="mb-3">
    <label for="roles" class="form-label">Role</label>
    <select class="form-select @error('roles') is-invalid @enderror" id="roles" name="roles[]" required>
        <option value="">-- Select Role --</option>
        @foreach ($roles as $role)
        <option value="{{ $role->name }}" {{ (isset($user) && $user->hasRole($role->name)) ? 'selected' : '' }}
            >{{ $role->name }}</option>
        @endforeach
    </select>
    @error('roles')<span class='text-danger'>{{ $message }}</span>@enderror
</div>

<button type="submit" class="btn btn-primary">{{ isset($user) ? 'Update User' : 'Create User' }}</button>
<a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>