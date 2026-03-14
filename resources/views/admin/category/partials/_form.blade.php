<div class="row g-3">

    @if(auth()->user()->role === 'admin')
    <div class="col-12">
        <label for="shop_id" class="form-label fw-bold">ร้านค้า (Shop) <span class="text-danger">*</span></label>
        <select name="shop_id" id="shop_id" class="form-select @error('shop_id') is-invalid @enderror" required>
            <option value="">-- กรุณาเลือกร้านค้า --</option>
            @foreach($shops as $shop)
                <option value="{{ $shop->id }}"
                    @selected(old('shop_id', $category->shop_id ?? '') == $shop->id)>
                    {{ $shop->name }}
                </option>
            @endforeach
        </select>
        @error('shop_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    @endif

    <div class="col-12">
        <label for="name" class="form-label fw-bold">ชื่อหมวดหมู่ <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $category->name ?? '') }}" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="image" class="form-label fw-bold">รูปภาพหมวดหมู่</label>
        <input type="file" name="image" id="image" accept="image/*"
               class="form-control @error('image') is-invalid @enderror">
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if(isset($category) && $category->image_path)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $category->image_path) }}" class="rounded shadow-sm" style="max-height: 100px;">
                <div class="form-text">รูปภาพปัจจุบัน</div>
            </div>
        @endif
    </div>

    <div class="col-12 mt-4">
        <div class="form-check form-switch border p-3 rounded bg-light">
            <input class="form-check-input ms-0 me-2" type="checkbox" role="switch"
                   name="is_active" id="is_active" value="1"
                   @checked(old('is_active', isset($category) ? $category->is_active : true))>
            <label class="form-check-label fw-bold text-dark" for="is_active">เปิดใช้งาน (Active)</label>
            <div class="form-text text-muted ms-5">หมวดหมู่นี้จะแสดงในหน้าจอขายสินค้า (POS)</div>
        </div>
    </div>

</div>
