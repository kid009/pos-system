<div class="row g-3">

    <div class="col-md-6">
        <label for="sku" class="form-label fw-bold">รหัสสินค้า (SKU) <span class="text-danger">*</span></label>
        <input type="text" name="sku" id="sku"
               class="form-control @error('sku') is-invalid @enderror"
               value="{{ old('sku', $product->sku ?? '') }}" required autofocus>
        @error('sku')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="category_id" class="form-label fw-bold">หมวดหมู่</label>
        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
            <option value="">-- ไม่มีหมวดหมู่ --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="name" class="form-label fw-bold">ชื่อสินค้า <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $product->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="price" class="form-label fw-bold">ราคาขาย <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" name="price" id="price" step="0.01" min="0"
                   class="form-control @error('price') is-invalid @enderror"
                   value="{{ old('price', $product->price ?? '') }}" required>
            <span class="input-group-text">บาท</span>
        </div>
        @error('price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="cost" class="form-label fw-bold">ต้นทุน</label>
        <div class="input-group">
            <input type="number" name="cost" id="cost" step="0.01" min="0"
                   class="form-control @error('cost') is-invalid @enderror"
                   value="{{ old('cost', $product->cost ?? '') }}">
            <span class="input-group-text">บาท</span>
        </div>
        @error('cost')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="stock_qty" class="form-label fw-bold">จำนวนสต็อก</label>
        <input type="number" name="stock_qty" id="stock_qty" min="0"
               class="form-control @error('stock_qty') is-invalid @enderror"
               value="{{ old('stock_qty', $product->stock_qty ?? 0) }}">
        @error('stock_qty')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="unit" class="form-label fw-bold">หน่วยนับ</label>
        <input type="text" name="unit" id="unit"
               class="form-control @error('unit') is-invalid @enderror"
               value="{{ old('unit', $product->unit ?? '') }}"
               placeholder="เช่น ชิ้น, กล่อง, ถุง">
        @error('unit')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="image" class="form-label fw-bold">URL รูปภาพ</label>
        <input type="text" name="image" id="image"
               class="form-control @error('image') is-invalid @enderror"
               value="{{ old('image', $product->image ?? '') }}">
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="affiliate_link" class="form-label fw-bold">ลิงก์ Affiliate</label>
        <input type="text" name="affiliate_link" id="affiliate_link"
               class="form-control @error('affiliate_link') is-invalid @enderror"
               value="{{ old('affiliate_link', $product->affiliate_link ?? '') }}">
        @error('affiliate_link')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mt-4">
        <div class="form-check form-switch border p-3 rounded bg-light">
            <input class="form-check-input ms-0 me-2" type="checkbox" role="switch"
                   name="is_active" id="is_active" value="1"
                   @checked(old('is_active', isset($product) ? $product->is_active : true))>
            <label class="form-check-label fw-bold text-dark" for="is_active">เปิดใช้งาน (Active)</label>
            <div class="form-text text-muted ms-5">สินค้าที่เปิดใช้งานจะแสดงในระบบ POS</div>
        </div>
    </div>

</div>