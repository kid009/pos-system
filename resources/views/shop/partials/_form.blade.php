<div class="row g-3">

    <div class="col-12">
        <label for="name" class="form-label fw-bold">ชื่อร้าน <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $shop->name ?? '') }}" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="branch_code" class="form-label fw-bold">รหัสสาขา</label>
        <input type="text" name="branch_code" id="branch_code"
            class="form-control @error('branch_code') is-invalid @enderror"
            value="{{ old('branch_code', $shop->branch_code ?? '') }}" placeholder="เช่น 00000">
        @error('branch_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="tax_id" class="form-label fw-bold">เลขประจำตัวผู้เสียภาษี</label>
        <input type="text" name="tax_id" id="tax_id" class="form-control @error('tax_id') is-invalid @enderror"
            value="{{ old('tax_id', $shop->tax_id ?? '') }}">
        @error('tax_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="phone" class="form-label fw-bold">เบอร์โทรศัพท์</label>
        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone', $shop->phone ?? '') }}">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="address" class="form-label fw-bold">ที่อยู่</label>
        <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $shop->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mt-4">
        <div class="form-check form-switch border p-3 rounded bg-light">
            <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" name="is_active" id="is_active"
                value="1" @checked(old('is_active', isset($shop) ? $shop->is_active : true))>
            <label class="form-check-label fw-bold text-dark" for="is_active">เปิดใช้งาน (Active)</label>
            <div class="form-text text-muted ms-5">หากปิดการใช้งาน ร้านค้านี้จะไม่ปรากฏในระบบขายหน้าร้าน</div>
        </div>
    </div>

</div>
