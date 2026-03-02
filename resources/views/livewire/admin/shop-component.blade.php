<div class="container-fluid px-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center my-4">
        <div>
            <h3 class="fw-bold text-primary"><i class="fas fa-store me-2"></i>จัดการร้านค้า (My Shops)</h3>
            <p class="text-muted small mb-0">เพิ่มและแก้ไขข้อมูลร้านค้าของคุณ</p>
        </div>

        @hasrole('admin')
        <button wire:click="create" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> เปิดร้านใหม่
        </button>
        @endhasrole

    </div>

    <!-- Shop List -->
    <div class="row g-4">
        <!-- Search Bar -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-2">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-0"
                            placeholder="ค้นหาร้านค้า...">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>โลโก้</th>
                                    <th>ชื่อร้านค้า</th>
                                    <th>เบอร์โทรศัพท์</th>
                                    <th>ที่อยู่ร้าน</th>
                                    <th>แก้ไข</th>
                                    <th>ลบข้อมูล</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shops as $shop)
                                    <tr>
                                        <td class="text-center">
                                            @if ($shop->logo_path)
                                                <img src="{{ asset('storage/' . $shop->logo_path) }}"
                                                    class="rounded-circle border" width="60" height="60"
                                                    style="object-fit: cover;">
                                            @else
                                                <img src="https://img.freepik.com/free-vector/search-concept-yellow-folder-magnifier-icons-hand-drawn-cartoon-art-illustration_56104-891.jpg?t=st=1771170185~exp=1771173785~hmac=c6be3cb1501fe65bb66947d230ad3dcec34e96d688ff878f8f00b530ecf8419f&w=1480"
                                                    class="rounded-circle border" width="60" height="60"
                                                    style="object-fit: cover;">
                                            @endif
                                        </td>
                                        <td>{{ $shop->name }}</td>
                                        <td>{{ $shop->phone ?? '-' }}</td>
                                        <td>{{ $shop->address ?? '-' }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-warning" wire:click="edit({{ $shop->id }})">
                                                <i class="fas fa-edit me-2"></i>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-danger"
                                                wire:confirm="ยืนยันการลบร้าน '{{ $shop->name }}' ? ข้อมูลสินค้าทั้งหมดจะหายไป!"
                                                wire:click="delete({{ $shop->id }})">
                                                <i class="fas fa-trash me-2"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $shops->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- 🔥 MODAL (Create/Edit Shop) 🔥 -->
        <!-- ============================================== -->
        <div x-data="{ open: false }" x-show="open" x-on:show-modal.window="open = true"
            x-on:close-modal.window="open = false" x-on:keydown.escape.window="open = false" x-cloak
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 2000;">

            <div class="position-absolute w-100 h-100 bg-dark bg-opacity-50" @click="open = false"></div>

            <div
                class="position-relative w-100 h-100 d-flex align-items-center justify-content-center pointer-events-none">
                <div class="bg-white rounded shadow-lg w-100 m-3" style="max-width: 600px; pointer-events: auto;">

                    <!-- Header -->
                    <div
                        class="modal-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 fw-bold">
                            {{ $editingId ? '✏️ แก้ไขข้อมูลร้าน' : '🏪 เปิดร้านใหม่' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="open = false"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body p-4">
                        <form wire:submit.prevent="save">

                            <!-- Logo Upload -->
                            <div class="mb-4 text-center">
                                <div class="position-relative d-inline-block">
                                    @if ($logo)
                                        <img src="{{ $logo->temporaryUrl() }}" class="rounded-circle border shadow-sm"
                                            width="100" height="100" style="object-fit: cover;">
                                    @elseif($oldLogo)
                                        <img src="{{ asset('storage/' . $oldLogo) }}"
                                            class="rounded-circle border shadow-sm" width="100" height="100"
                                            style="object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center mx-auto"
                                            style="width: 100px; height: 100px;">
                                            <i class="fas fa-camera fa-2x text-muted opacity-50"></i>
                                        </div>
                                    @endif

                                    <label for="logoInput"
                                        class="position-absolute bottom-0 end-0 bg-white border rounded-circle shadow-sm p-2 cursor-pointer"
                                        style="cursor: pointer;">
                                        <i class="fas fa-pencil-alt small text-primary"></i>
                                        <input type="file" id="logoInput" wire:model="logo" class="d-none"
                                            accept="image/*">
                                    </label>
                                </div>
                                @error('logo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">ชื่อร้านค้า <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name" class="form-control"
                                    placeholder="เช่น ร้านกาแฟโบราณ, ร้านแก๊สเจ๊หมวย">
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">เบอร์โทรศัพท์</label>
                                    <input type="text" wire:model="phone" class="form-control"
                                        placeholder="081-xxxxxxx">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">ที่อยู่ร้าน</label>
                                    <textarea wire:model="address" class="form-control" rows="1" placeholder="บ้านเลขที่, ตำบล..."></textarea>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                <button type="button" class="btn btn-secondary px-3"
                                    @click="open = false">ยกเลิก</button>
                                <button type="submit" class="btn btn-success px-4">
                                    <span wire:loading.remove>บันทึก</span>
                                    <span wire:loading><i class="fas fa-spinner fa-spin"></i> กำลังบันทึก...</span>
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>

        <style>
            .hover-card {
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .hover-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            }
        </style>
    </div>
