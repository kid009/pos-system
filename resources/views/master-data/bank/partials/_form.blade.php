<div class="row g-3">

    <div class="col-12">
        <label for="name" class="form-label fw-bold">ชื่อธนาคาร <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $bank->name ?? '') }}" autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="code" class="form-label fw-bold">รหัสย่อ <span class="text-danger">*</span></label>
        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $bank->code ?? '') }}">
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="account_name" class="form-label fw-bold">ชื่อบัญชี</label>
        <input type="text" name="account_name" id="account_name"
            class="form-control @error('account_name') is-invalid @enderror"
            value="{{ old('account_name', $bank->account_name ?? '') }}">
        @error('account_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="account_no" class="form-label fw-bold">เลขที่บัญชี</label>
        <input type="text" name="account_no" id="account_no"
            class="form-control @error('account_no') is-invalid @enderror"
            value="{{ old('account_no', $bank->account_no ?? '') }}">
        @error('account_no')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mt-4">
        <div class="form-check form-switch border p-3 rounded bg-light">
            <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" name="is_active" id="is_active"
                value="1" @checked(old('is_active', isset($bank) ? $bank->is_active : true))>
            <label class="form-check-label fw-bold text-dark" for="is_active">เปิดใช้งาน (Active)</label>
            <div class="form-text text-muted ms-5">ธนาคารที่เปิดใช้งานจะแสดงในระบบ</div>
        </div>
    </div>

</div>
