<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label" for="name">ชื่อหมวดหมู่</label>
                <input class="form-control @error('name') is-invalid @enderror" 
                        id="name" 
                        name="name" 
                        type="text"
                        value="{{ old('name', $category->name ?? '') }}">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label" for="description">คำอธิบาย (ถ้ามี)</label>
                <input class="form-control" 
                        id="description" 
                        name="description" type="text"
                        value="{{ old('description', $category->description ?? '') }}">
            </div>
        </div>
    </div>
</div>
<div class="card-footer text-end">
    <button class="btn btn-primary" type="submit">บันทึก</button>
    <a href="{{ route('admin.product-categories.index') }}" class="btn btn-secondary">ยกเลิก</a>
</div>