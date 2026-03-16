<div class="row g-3">

    @if(auth()->user()->role === 'admin')
    <div class="col-md-6">
        <label for="shop_id" class="form-label fw-bold">ร้านค้า (Shop) <span class="text-danger">*</span></label>
        <select name="shop_id" id="shop_id" class="form-select @error('shop_id') is-invalid @enderror" required>
            <option value="">-- กรุณาเลือกร้านค้า --</option>
            @foreach($shops as $shop)
                <option value="{{ $shop->id }}" @selected(old('shop_id', $product->shop_id ?? '') == $shop->id)>
                    {{ $shop->name }}
                </option>
            @endforeach
        </select>
        @error('shop_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    @endif

    <div class="col-md-6">
        <label for="category_id" class="form-label fw-bold">หมวดหมู่</label>
        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
            <option value="">-- ไม่ระบุ --</option>

            @if(auth()->user()->role === 'admin')
                @foreach($shops as $shop)
                    @if($shop->categories->count() > 0)
                        <optgroup label="ร้าน: {{ $shop->name }}">
                            @foreach($shop->categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endif
                @endforeach
            @else
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            @endif
        </select>
        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="sku" class="form-label fw-bold">รหัสสินค้า / บาร์โค้ด</label>
        <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku ?? '') }}">
        @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-8">
        <label for="name" class="form-label fw-bold">ชื่อสินค้า <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="cost" class="form-label fw-bold">ต้นทุน <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">฿</span>
            <input type="number" step="0.01" min="0" name="cost" id="cost" class="form-control @error('cost') is-invalid @enderror" value="{{ old('cost', $product->cost ?? '0') }}" required>
        </div>
        @error('cost') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="price" class="form-label fw-bold">ราคาขาย <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">฿</span>
            <input type="number" step="0.01" min="0" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price ?? '0') }}" required>
        </div>
        @error('price') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="unit" class="form-label fw-bold">หน่วยนับ</label>
        <input type="text" name="unit" id="unit" placeholder="เช่น ถัง, ขวด" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', $product->unit ?? '') }}">
        @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12 mt-4">
        <label for="image" class="form-label fw-bold">รูปภาพสินค้า</label>
        <input type="file" name="image" id="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

        @if(isset($product) && $product->image_path)
            <div class="mt-2">
                <img src="{{ asset('images/' . $product->image_path) }}" class="rounded shadow-sm" style="max-height: 100px;">
            </div>
        @endif
    </div>

    <div class="col-12 mt-4">
        <div class="form-check form-switch border p-3 rounded bg-light">
            <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" name="is_active" id="is_active" value="1" @checked(old('is_active', isset($product) ? $product->is_active : true))>
            <label class="form-check-label fw-bold text-dark" for="is_active">เปิดใช้งาน (ขายหน้าร้านได้)</label>
        </div>
    </div>

</div>
