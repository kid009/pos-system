<div class="row g-3">

    <div class="col-12">
        <label for="name" class="form-label fw-bold">ชื่อ-นามสกุล / บริษัท <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $customer->name ?? '') }}" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="phone" class="form-label fw-bold">เบอร์โทรศัพท์</label>
        <input type="text" name="phone" id="phone"
               class="form-control @error('phone') is-invalid @enderror"
               value="{{ old('phone', $customer->phone ?? '') }}">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="branch" class="form-label fw-bold">สาขา</label>
        <input type="text" name="branch" id="branch"
               class="form-control @error('branch') is-invalid @enderror"
               value="{{ old('branch', $customer->branch ?? '') }}">
        @error('branch')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="tax_id" class="form-label fw-bold">เลขประจำตัวผู้เสียภาษี</label>
        <input type="text" name="tax_id" id="tax_id"
               class="form-control @error('tax_id') is-invalid @enderror"
               value="{{ old('tax_id', $customer->tax_id ?? '') }}">
        @error('tax_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="address" class="form-label fw-bold">ที่อยู่</label>
        <textarea name="address" id="address" rows="3"
                  class="form-control @error('address') is-invalid @enderror">{{ old('address', $customer->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="latitude" class="form-label fw-bold">ละติจูด (Latitude)</label>
        <input type="number" name="latitude" id="latitude" step="any"
               class="form-control @error('latitude') is-invalid @enderror"
               value="{{ old('latitude', $customer->latitude ?? '') }}">
        @error('latitude')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="longitude" class="form-label fw-bold">ลองจิจูด (Longitude)</label>
        <input type="number" name="longitude" id="longitude" step="any"
               class="form-control @error('longitude') is-invalid @enderror"
               value="{{ old('longitude', $customer->longitude ?? '') }}">
        @error('longitude')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mt-4">
        <div class="form-check form-switch border p-3 rounded bg-light">
            <input class="form-check-input ms-0 me-2" type="checkbox" role="switch"
                   name="is_active" id="is_active" value="1"
                   @checked(old('is_active', isset($customer) ? $customer->is_active : true))>
            <label class="form-check-label fw-bold text-dark" for="is_active">เปิดใช้งาน (Active)</label>
            <div class="form-text text-muted ms-5">ลูกค้าที่เปิดใช้งานจะแสดงในระบบ</div>
        </div>
    </div>

</div>