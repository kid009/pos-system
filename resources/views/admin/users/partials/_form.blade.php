<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label fw-bold">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name ?? '') }}" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label fw-bold">อีเมล <span class="text-danger">*</span></label>
        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label fw-bold">รหัสผ่าน {{ isset($user) ? '(ปล่อยว่างถ้าไม่ต้องการเปลี่ยน)' : '*' }}</label>
        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" {{ isset($user) ? '' : 'required' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label fw-bold">ยืนยันรหัสผ่าน</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" {{ isset($user) ? '' : 'required' }}>
    </div>

    <div class="col-md-6">
        <label for="role" class="form-label fw-bold">บทบาท (Role) <span class="text-danger">*</span></label>
        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="staff" @selected(old('role', $user->role ?? 'staff') == 'staff')>Staff (พนักงานขาย)</option>
            <option value="owner" @selected(old('role', $user->role ?? '') == 'owner')>Owner (เจ้าของร้าน)</option>
            <option value="admin" @selected(old('role', $user->role ?? '') == 'admin')>Admin (ผู้ดูแลระบบ)</option>
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="shop_id" class="form-label fw-bold">สังกัดร้านค้า (Shop)</label>
        <select name="shop_id" id="shop_id" class="form-select @error('shop_id') is-invalid @enderror">
            <option value="">-- ไม่ระบุ (สำหรับ Admin) --</option>
            @foreach($shops as $shop)
                <option value="{{ $shop->id }}" @selected(old('shop_id', $user->shop_id ?? '') == $shop->id)>
                    {{ $shop->name }}
                </option>
            @endforeach
        </select>
        <div class="form-text">พนักงานจะเห็นข้อมูลเฉพาะร้านที่สังกัดเท่านั้น</div>
        @error('shop_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="form-check form-switch p-3 border rounded bg-light">
            <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" name="is_active" id="is_active" value="1" @checked(old('is_active', $user->is_active ?? true))>
            <label class="form-check-label fw-bold" for="is_active">สถานะเปิดใช้งาน (Active)</label>
        </div>
    </div>
</div>
