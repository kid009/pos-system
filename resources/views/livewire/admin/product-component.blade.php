<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center my-4">
        <h3 class="fw-bold text-primary">📦 สินค้า (Products)</h3>
        <button wire:click="create" class="btn btn-primary shadow-sm">
            <i class="fas fa-box-open me-1"></i> เพิ่มสินค้า
        </button>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">

            <div class="mb-3 w-25">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                    placeholder="🔍 ค้นหาสินค้า...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50px;">รูป</th>
                            <th>ชื่อสินค้า</th>
                            <th>หมวดหมู่</th>
                            <th class="text-end">ราคาขาย</th>
                            <th class="text-center">สต็อก</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                            <tr>
                                <td>
                                    @if ($p->image_path)
                                        <img src="{{ asset('storage/' . $p->image_path) }}" class="rounded shadow-sm" width="40"
                                            height="40" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded" style="width: 40px; height: 40px;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold text-dark">{{ $p->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $p->category->name ?? 'ไม่ระบุ' }}</span>
                                </td>
                                <td class="text-end text-success fw-bold">{{ number_format($p->price, 2) }}</td>
                                <td class="text-center">
                                    @if ($p->category && !$p->category->is_tracking_stock)
                                        <span class="text-muted small"><i class="fas fa-minus"></i> ไม่นับ</span>
                                    @else
                                        <span class="badge {{ $p->stock_qty > 0 ? 'bg-info text-dark' : 'bg-danger' }}">
                                            {{ $p->stock_qty }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($p->is_active)
                                        <i class="fas fa-check-circle text-success fs-5"></i>
                                    @else
                                        <i class="fas fa-times-circle text-muted fs-5"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button wire:click="edit({{ $p->id }})"
                                        class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:confirm="คุณแน่ใจหรือไม่ที่จะลบสินค้า '{{ $p->name }}' ?" wire:click="delete({{ $p->id }})"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">ไม่พบข้อมูลสินค้า</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <div x-data="{ open: false }" x-show="open" x-on:show-modal.window="open = true"
        x-on:close-modal.window="open = false" x-cloak
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 2000;">

        <div class="position-absolute w-100 h-100 bg-dark bg-opacity-50" @click="open = false"></div>

        <div class="position-relative w-100 h-100 d-flex align-items-center justify-content-center pointer-events-none">
            <div class="bg-white rounded shadow-lg w-100 m-3" style="max-width: 600px; pointer-events: auto;">

                <div class="modal-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold">{{ $form->product ? '✏️ แก้ไขสินค้า' : '➕ เพิ่มสินค้าใหม่' }}</h5>
                    <button type="button" class="btn-close btn-close-white" @click="open = false"></button>
                </div>

                <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
                    <form wire:submit="save">

                        <div class="mb-3">
                            <label class="form-label fw-bold">หมวดหมู่ <span class="text-danger">*</span></label>
                            <select wire:model.live="form.category_id" class="form-select @error('form.category_id') is-invalid @enderror">
                                <option value="">-- เลือกหมวดหมู่ --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('form.category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">ชื่อสินค้า <span class="text-danger">*</span></label>
                                <input type="text" wire:model="form.name" class="form-control @error('form.name') is-invalid @enderror">
                                @error('form.name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">ราคาทุน</label>
                                <input type="number" step="0.01" wire:model="form.cost" class="form-control text-end @error('form.cost') is-invalid @enderror">
                                @error('form.cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">ราคาขาย <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" wire:model="form.price"
                                    class="form-control text-end text-success fw-bold @error('form.price') is-invalid @enderror">
                                @error('form.price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                @if ($form->is_tracking_stock)
                                    <label class="form-label fw-bold">จำนวนสต็อก <span class="text-danger">*</span></label>
                                    <input type="number" wire:model="form.stock_qty" class="form-control text-center @error('form.stock_qty') is-invalid @enderror">
                                    @error('form.stock_qty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @else
                                    <div class="h-100 d-flex flex-column justify-content-end pb-2">
                                        <span class="text-muted fst-italic small">
                                            <i class="fas fa-info-circle"></i> หมวดหมู่นี้<br>ไม่นับสต็อก
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">รูปภาพสินค้า</label>
                            <input type="file" wire:model="form.image" class="form-control @error('form.image') is-invalid @enderror" accept="image/*">

                            <div wire:loading wire:target="form.image" class="text-info small mt-1">
                                <i class="fas fa-spinner fa-spin"></i> กำลังอัปโหลด...
                            </div>

                            @if ($form->image)
                                <img src="{{ $form->image->temporaryUrl() }}" class="mt-2 rounded shadow-sm border" width="100" height="100" style="object-fit: cover;">
                            @elseif($form->oldImage)
                                <img src="{{ asset('storage/' . $form->oldImage) }}" class="mt-2 rounded shadow-sm border" width="100" height="100" style="object-fit: cover;">
                            @endif

                            @error('form.image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch fs-6 mb-3">
                            <input class="form-check-input" type="checkbox" wire:model="form.is_active" id="activeSwitch">
                            <label class="form-check-label fw-bold mt-1 ms-2" for="activeSwitch">เปิดใช้งาน (Active)</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="button" class="btn btn-light border px-3" @click="open = false">ยกเลิก</button>
                            <button type="submit" class="btn btn-success px-4" wire:loading.attr="disabled" wire:target="save">
                                <span wire:loading.remove wire:target="save"><i class="fas fa-save me-1"></i> บันทึก</span>
                                <span wire:loading wire:target="save">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> บันทึก...
                                </span>
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

</div>
