<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label fw-bold">สาขาที่สังกัด <span class="text-danger">*</span></label>
        <select name="shop_id" class="form-select" required>
            @foreach ($shops as $shop)
                <option value="{{ $shop->id }}"
                    {{ old('shop_id', $customer->shop_id ?? '') == $shop->id ? 'selected' : '' }}>
                    {{ $shop->name }}
                </option>
            @endforeach
        </select>
        @error('shop_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-bold">ชื่อ-นามสกุล / บริษัท <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name ?? '') }}"
            required>
        @error('name')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label fw-bold">เบอร์โทรศัพท์</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone ?? '') }}"
            placeholder="08x-xxx-xxxx">
        @error('phone')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-bold">เลขประจำตัวผู้เสียภาษี (ถ้ามี)</label>
        <input type="text" name="tax_id" class="form-control" value="{{ old('tax_id', $customer->tax_id ?? '') }}"
            placeholder="เลข 13 หลัก">
        @error('tax_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-4">
    <label class="form-label fw-bold">ที่อยู่จัดส่ง / เปิดบิล</label>
    <textarea name="address" class="form-control" rows="3">{{ old('address', $customer->address ?? '') }}</textarea>
    @error('address')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="d-flex justify-content-end gap-2 border-top pt-4">
    <a href="{{ route('customers.index') }}" class="btn btn-light">ยกเลิก</a>
    <button type="submit" class="btn btn-primary px-4">
        <span data-feather="save" style="width: 18px;"></span> บันทึกข้อมูล
    </button>
</div>
