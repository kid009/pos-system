<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center my-4">
        <h3 class="fw-bold text-primary">📦 สินค้า (Products)</h3>
        <button wire:click="create" class="btn btn-primary shadow-sm">
            <i class="fas fa-box-open me-1"></i> เพิ่มสินค้า
        </button>
    </div>

    <!-- Content -->
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
                            <th>บาร์โค้ด</th>
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
                                        <img src="{{ asset('storage/' . $p->image_path) }}" class="rounded" width="40"
                                            height="40" style="object-fit: cover;">
                                    @else
                                        <span class="text-muted"><i class="fas fa-image fa-lg"></i></span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $p->barcode ?? '-' }}</td>
                                <td class="fw-bold text-dark">{{ $p->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $p->category->name ?? 'N/A' }}</span>
                                </td>
                                <td class="text-end text-success fw-bold">{{ number_format($p->price, 2) }}</td>
                                <td class="text-center">
                                    @if (str_contains($p->category->name ?? '', 'น้ำแก๊ส'))
                                        <span class="text-muted small">- ไม่นับ -</span>
                                    @else
                                        <span class="badge {{ $p->stock_qty > 0 ? 'bg-info text-dark' : 'bg-danger' }}">
                                            {{ $p->stock_qty }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($p->is_active)
                                        <i class="fas fa-check-circle text-success"></i>
                                    @else
                                        <i class="fas fa-times-circle text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button wire:click="edit({{ $p->id }})"
                                        class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:confirm="ลบสินค้า?" wire:click="delete({{ $p->id }})"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">ไม่พบข้อมูล</td>
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

    <!-- MODAL -->
    <div x-data="{ open: false }" x-show="open" x-on:show-modal.window="open = true"
        x-on:close-modal.window="open = false" x-cloak
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 2000;">

        <div class="position-absolute w-100 h-100 bg-dark bg-opacity-50" @click="open = false"></div>

        <div class="position-relative w-100 h-100 d-flex align-items-center justify-content-center pointer-events-none">
            <div class="bg-white rounded shadow-lg w-100 m-3" style="max-width: 600px; pointer-events: auto;">

                <div class="modal-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold">{{ $editingId ? '✏️ แก้ไขสินค้า' : '➕ เพิ่มสินค้าใหม่' }}</h5>
                    <button type="button" class="btn-close btn-close-white" @click="open = false"></button>
                </div>

                <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
                    <form wire:submit.prevent="save">

                        <!-- หมวดหมู่ (สำคัญมาก: เลือกแล้วจะเช็คว่าเป็นน้ำแก๊สไหม) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">หมวดหมู่ <span class="text-danger">*</span></label>
                            <select wire:model.live="category_id" class="form-select">
                                <option value="">-- เลือกหมวดหมู่ --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">บาร์โค้ด</label>
                                <input type="text" wire:model="barcode" class="form-control"
                                    placeholder="สแกนหรือพิมพ์...">
                                @error('barcode')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">ชื่อสินค้า <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name" class="form-control">
                                @error('name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">ราคาทุน</label>
                                <input type="number" step="0.01" wire:model="cost" class="form-control text-end">
                                @error('cost')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">ราคาขาย <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" wire:model="price"
                                    class="form-control text-end text-success fw-bold">
                                @error('price')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ช่อง Stock: จะแสดงก็ต่อเมื่อไม่ใช่ "น้ำแก๊ส" -->
                            @if (!$isGasCategory)
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">จำนวนสต็อก <span
                                            class="text-danger">*</span></label>
                                    <input type="number" wire:model="stock_qty" class="form-control text-center">
                                    @error('stock_qty')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            @else
                                <div class="col-md-4 mb-3 d-flex align-items-center">
                                    <span class="text-muted fst-italic small mt-4">
                                        <i class="fas fa-info-circle"></i> หมวดน้ำแก๊ส<br>ไม่นับสต็อกในระบบ
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">รูปภาพสินค้า</label>
                            <input type="file" wire:model="image" class="form-control">
                            <div wire:loading wire:target="image" class="text-info small mt-1">กำลังอัปโหลด...</div>
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" class="mt-2 rounded" width="100">
                            @elseif($oldImage)
                                <img src="{{ asset('storage/' . $oldImage) }}" class="mt-2 rounded" width="100">
                            @endif
                            @error('image')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" wire:model="is_active"
                                id="activeSwitch">
                            <label class="form-check-label" for="activeSwitch">เปิดใช้งาน (Active)</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="button" class="btn btn-secondary px-3"
                                @click="open = false">ยกเลิก</button>
                            <button type="submit" class="btn btn-success px-3">
                                <i class="fas fa-save me-1"></i> บันทึก
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

</div>
