<div class="row g-3">

    <div class="col-12">
        <label for="name" class="form-label fw-bold">ชื่อบริษัทขนส่ง <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $shippingMethod->name ?? '') }}" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mt-4">
        <div class="form-check form-switch border p-3 rounded bg-light">
            <input class="form-check-input ms-0 me-2" type="checkbox" role="switch"
                   name="is_active" id="is_active" value="1"
                   @checked(old('is_active', isset($shippingMethod) ? $shippingMethod->is_active : true))>
            <label class="form-check-label fw-bold text-dark" for="is_active">เปิดใช้งาน (Active)</label>
            <div class="form-text text-muted ms-5">บริษัทขนส่งที่เปิดใช้งานจะแสดงในระบบ</div>
        </div>
    </div>

</div>