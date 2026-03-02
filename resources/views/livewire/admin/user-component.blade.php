<div class="container-fluid px-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center my-4">
        <div>
            <h3 class="fw-bold text-primary mb-0"><i class="fas fa-users me-2"></i>จัดการพนักงาน/ผู้ใช้</h3>
            <p class="text-muted small mb-0">
                {{ $isAdmin ? 'จัดการผู้ใช้งานและร้านค้าทั้งหมดในระบบ' : 'จัดการพนักงานภายในร้านของคุณ' }}
            </p>
        </div>
        <button wire:click="create" class="btn btn-primary shadow-sm">
            <i class="fas fa-user-plus me-1"></i> เพิ่มผู้ใช้ใหม่
        </button>
    </div>

    <!-- Search & Table -->
    <div class="card shadow border-0 mb-4">
        <div class="card-body">

            <!-- Search Bar -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="input-group w-50">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-start-0 ps-0" placeholder="🔍 ค้นหาชื่อ หรือ อีเมล...">
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">ชื่อ - นามสกุล</th>
                            <th>อีเมล</th>
                            <th>ตำแหน่ง (Role)</th>
                            @if($isAdmin) <th>สังกัดร้านค้า</th> @endif
                            <th class="text-center" style="width: 150px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary fw-bold rounded-circle d-flex justify-content-center align-items-center me-3 border border-primary border-opacity-25" style="width: 40px; height: 40px;">
                                        {{ mb_substr($u->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $u->name }}</div>
                                        @if($u->id === auth()->id())
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25" style="font-size: 0.7em;">ฉันเอง (You)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ $u->email }}</td>
                            <td>
                                @if($u->role === 'admin')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">ผู้ดูแลระบบส่วนกลาง</span>
                                @elseif($u->role === 'shop_owner')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">เจ้าของร้าน</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">พนักงาน</span>
                                @endif
                            </td>

                            @if($isAdmin)
                                <td>
                                    @if($u->role === 'admin')
                                        <span class="text-muted small"><i class="fas fa-globe me-1"></i> ทุกร้าน (Global)</span>
                                    @else
                                        @if($u->shops->count() > 0)
                                            <span class="badge bg-info bg-opacity-10 text-dark border border-info">
                                                <i class="fas fa-store me-1"></i> {{ $u->shops->first()->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i> ไม่มีสังกัด
                                            </span>
                                        @endif
                                    @endif
                                </td>
                            @endif

                            <td class="text-center">
                                <div class="btn-group">
                                    <!-- ปุ่มแก้ไข -->
                                    <button wire:click="edit({{ $u->id }})" class="btn btn-sm btn-outline-warning" title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- ปุ่มลบ (ซ่อนถ้าเป็นตัวเอง) -->
                                    @if($u->id !== auth()->id())
                                        <button wire:confirm="⚠️ ยืนยันการลบผู้ใช้งาน '{{ $u->name }}' หรือไม่?"
                                                wire:click="delete({{ $u->id }})"
                                                class="btn btn-sm btn-outline-danger" title="ลบ">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary disabled" title="ไม่สามารถลบตัวเองได้">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 5 : 4 }}" class="text-center py-5 text-muted">
                                <i class="fas fa-users-slash fa-3x mb-3 opacity-25"></i>
                                <h5>ไม่พบข้อมูลผู้ใช้งาน</h5>
                                <p>กดปุ่ม "เพิ่มผู้ใช้ใหม่" เพื่อสร้างบัญชี</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 🔥 MODAL (Create/Edit User) 🔥 -->
    <!-- ============================================== -->
    <div x-data="{ open: false }"
         x-show="open"
         x-on:show-modal.window="open = true"
         x-on:close-modal.window="open = false"
         x-on:keydown.escape.window="open = false"
         x-cloak
         style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 2000;">

        <div class="position-absolute w-100 h-100 bg-dark bg-opacity-50" @click="open = false"></div>

        <div class="position-relative w-100 h-100 d-flex align-items-center justify-content-center pointer-events-none">
            <div class="bg-white rounded shadow-lg w-100 m-3" style="max-width: 500px; pointer-events: auto;">

                <!-- Header -->
                <div class="modal-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold">
                        <i class="fas {{ $editingId ? 'fa-user-edit' : 'fa-user-plus' }} me-2"></i>
                        {{ $editingId ? 'แก้ไขข้อมูลพนักงาน' : 'เพิ่มผู้ใช้ใหม่' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" @click="open = false"></button>
                </div>

                <!-- Body -->
                <div class="modal-body p-4">
                    <form wire:submit.prevent="save">

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">ชื่อ - นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control" placeholder="เช่น สมชาย ใจดี">
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">อีเมล (ใช้เข้าสู่ระบบ) <span class="text-danger">*</span></label>
                            <input type="email" wire:model="email" class="form-control" placeholder="example@mail.com">
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">รหัสผ่าน <span class="text-danger">{{ $editingId ? '' : '*' }}</span></label>
                            <input type="password" wire:model="password" class="form-control" placeholder="{{ $editingId ? 'เว้นว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน' : 'ตั้งรหัสผ่านอย่างน้อย 6 ตัวอักษร' }}">
                            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Role (ใช้ .live เพื่อให้มันอัปเดตค่าไปยัง Component ทันทีเวลาเลือก) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">ระดับสิทธิ์ (Role) <span class="text-danger">*</span></label>
                            <select wire:model.live="role" class="form-select">
                                <option value="">-- เลือกตำแหน่ง --</option>
                                @if($isAdmin)
                                    <option value="admin">ผู้ดูแลระบบส่วนกลาง (Admin)</option>
                                    <option value="shop_owner">เจ้าของร้าน (Owner)</option>
                                @endif
                                <option value="staff">พนักงานทั่วไป (Staff)</option>
                            </select>
                            @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Shop Selection (Only for Admin AND Role is not 'admin') -->
                        @if($isAdmin && $role !== 'admin')
                        <div class="mb-4 p-3 bg-light border rounded">
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-store me-1"></i> สังกัดร้านค้า <span class="text-danger">*</span>
                            </label>
                            <select wire:model="shop_id" class="form-select border-primary shadow-sm">
                                <option value="">-- กรุณาเลือกร้านค้าที่สังกัด --</option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted mt-2"><i class="fas fa-info-circle"></i> พนักงาน 1 คน สามารถสังกัดได้เพียง 1 ร้านเท่านั้น</div>
                            @error('shop_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        @endif

                        <!-- Footer Buttons -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="button" class="btn btn-secondary px-3" @click="open = false">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary px-4">
                                <span wire:loading.remove wire:target="save">
                                    <i class="fas fa-save me-1"></i> บันทึกข้อมูล
                                </span>
                                <span wire:loading wire:target="save">
                                    <i class="fas fa-spinner fa-spin me-1"></i> กำลังบันทึก...
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
