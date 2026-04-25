<div class="row g-3">

    <div class="col-12">
        <label for="name" class="form-label fw-bold">ชื่อซัพพลายเออร์/บริษัท <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $supplier->name ?? '') }}" autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="contact_name" class="form-label fw-bold">ชื่อผู้ติดต่อ</label>
        <input type="text" name="contact_name" id="contact_name"
            class="form-control @error('contact_name') is-invalid @enderror"
            value="{{ old('contact_name', $supplier->contact_name ?? '') }}">
        @error('contact_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="phone" class="form-label fw-bold">เบอร์โทรศัพท์</label>
        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone', $supplier->phone ?? '') }}">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="tax_id" class="form-label fw-bold">เลขประจำตัวผู้เสียภาษี</label>
        <input type="text" name="tax_id" id="tax_id" class="form-control @error('tax_id') is-invalid @enderror"
            value="{{ old('tax_id', $supplier->tax_id ?? '') }}">
        @error('tax_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="address" class="form-label fw-bold">ที่อยู่</label>
        <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $supplier->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mt-4">
        <div class="form-check form-switch border p-3 rounded bg-light">
            <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" name="is_active" id="is_active"
                value="1" @checked(old('is_active', isset($supplier) ? $supplier->is_active : true))>
            <label class="form-check-label fw-bold text-dark" for="is_active">เปิดใช้งาน (Active)</label>
            <div class="form-text text-muted ms-5">ซัพพลายเออร์ที่เปิดใช้งานจะแสดงในระบบ</div>
        </div>
    </div>

</div>
