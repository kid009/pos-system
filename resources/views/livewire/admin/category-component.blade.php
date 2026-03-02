<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center my-4">
        <h3 class="fw-bold text-primary">📂 หมวดหมู่ (Categories)</h3>
        <button wire:click="create" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> เพิ่มรายการ
        </button>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">

            <div class="mb-3 w-25">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                    placeholder="🔍 ค้นหาหมวดหมู่...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            @if($isAdmin)
                                <th>ร้านค้า</th>
                            @endif
                            <th>หมวดหมู่</th>
                            <th class="text-center">ระบบนับสต็อก</th>
                            <th class="text-center">จำนวนสินค้า</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $item)
                            <tr>
                                @if($isAdmin)
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-store"></i> {{ $item->shop->name ?? 'ไม่ระบุร้านค้า' }}
                                    </span>
                                </td>
                                @endif
                                <td class="fw-bold text-dark">{{ $item->name }}</td>
                                <td class="text-center">
                                    @if ($item->is_tracking_stock)
                                        <span class="badge bg-success bg-opacity-75"><i class="fas fa-check-circle"></i>
                                            เปิด</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-75"><i
                                                class="fas fa-times-circle"></i> ปิด</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-info text-dark">
                                        {{ $item->products_count }} รายการ
                                    </span>
                                </td>

                                <td class="text-center">
                                    <button wire:click="edit({{ $item->id }})"
                                        class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:confirm="คุณแน่ใจหรือไม่ที่จะลบหมวดหมู่ '{{ $item->name }}' ?"
                                        wire:click="delete({{ $item->id }})" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">ไม่พบข้อมูล</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    <div x-data="{ open: false }" x-show="open" x-on:show-modal.window="open = true"
        x-on:close-modal.window="open = false" x-on:keydown.escape.window="open = false" x-cloak
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 2000;">

        <div class="position-absolute w-100 h-100 bg-dark bg-opacity-50" @click="open = false"></div>

        <div class="position-relative w-100 h-100 d-flex align-items-center justify-content-center pointer-events-none">
            <div class="bg-white rounded shadow-lg w-100 m-3" style="max-width: 500px; pointer-events: auto;">

                <div class="modal-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold">
                        {{ $form->category ? 'แก้ไขหมวดหมู่' : 'เพิ่มหมวดหมู่' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" @click="open = false"></button>
                </div>

                <div class="modal-body p-4">
                    <form wire:submit="save">

                        @if($isAdmin)
                        <div class="mb-3">
                            <label class="form-label fw-bold">ร้านค้า <span class="text-danger">*</span></label>
                            <select wire:model="form.shop_id"
                                class="form-select @error('form.shop_id') is-invalid @enderror">
                                <option value="">-- เลือกร้านค้า --</option>
                                @foreach ($shops as $shop)
                                    <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                @endforeach
                            </select>
                            @error('form.shop_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label fw-bold">ชื่อหมวดหมู่ <span class="text-danger">*</span></label>
                            <input type="text" wire:model="form.name"
                                class="form-control @error('form.name') is-invalid @enderror">
                            @error('form.name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch fs-5">
                                <input class="form-check-input" type="checkbox" id="isTrackingStock"
                                    wire:model="form.is_tracking_stock">
                                <label class="form-check-label fw-bold fs-6 mt-1 ms-2" for="isTrackingStock">
                                    เปิดระบบนับสต็อก
                                </label>
                            </div>
                            <small class="text-muted mt-1 d-block">หากปิด จะไม่ต้องระบุจำนวนสต็อกสำหรับสินค้าหมวดหมู่นี้
                                (เช่น ค่าบริการ, ค่าขนส่ง)</small>
                            @error('form.is_tracking_stock')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="button" class="btn btn-secondary px-3" @click="open = false">ยกเลิก</button>

                            <button type="submit" class="btn btn-success px-3" wire:loading.attr="disabled"
                                wire:target="save">
                                <span wire:loading.remove wire:target="save"><i class="fas fa-save me-1"></i>
                                    บันทึก</span>
                                <span wire:loading wire:target="save">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span> กำลังบันทึก...
                                </span>
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

</div>
