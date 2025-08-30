@csrf
<div class="mb-3">
    <label for="name" class="form-label">Role Name</label>
    {{-- เช็คว่ามี $role->name หรือไม่ (สำหรับหน้า Edit) ถ้าไม่มีให้เป็นค่าว่าง (สำหรับหน้า Create) --}}
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name', $role->name ?? '') }}">
    @error('name')
    <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label d-block">Permissions</label>
    <div class="row">
        {{-- วนลูปแสดงผล Permission โดยจัดกลุ่มตามชื่อ --}}
        @foreach ($permissions as $groupName => $permissionList)
        <div class="col-md-4 mb-4">
            <h6 class="mb-2 text-primary">{{ ucfirst($groupName) }} Management</h6>
            @foreach ($permissionList as $permission)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                    id="perm_{{ $permission->id }}" {{-- ตรวจสอบว่า permission นี้ควรถูกติ๊กหรือไม่ 1. เช็คจาก old input
                    (กรณี validation error) 2. ถ้าไม่มี old input ให้เช็คจาก $rolePermissions ที่ส่งมาจาก controller
                    (สำหรับหน้า Edit) 3. ถ้าไม่มีทั้งสองอย่าง (สำหรับหน้า Create) ก็จะไม่ติ๊ก --}} 
                    @if(in_array($permission->name, old('permissions', $rolePermissions ?? [])) )
                    checked
                    @endif
                >
                <label class="form-check-label" for="perm_{{ $permission->id }}">
                    {{ $permission->name }}
                </label>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

{{-- เช็คว่ามีตัวแปร $role อยู่หรือไม่ เพื่อเปลี่ยนข้อความบนปุ่ม --}}
<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>