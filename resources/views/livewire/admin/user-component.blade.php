<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center my-4">
        <h3 class="fw-bold text-primary"><i class="fas fa-users me-2"></i>จัดการพนักงาน/ผู้ใช้</h3>
        <button wire:click="create" class="btn btn-primary shadow-sm">
            <i class="fas fa-user-plus me-1"></i> เพิ่มผู้ใช้ใหม่
        </button>
    </div>

    <!-- Search & Table -->
    <div class="card shadow border-0">
        <div class="card-body">
            <div class="mb-3 w-25">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="🔍 ค้นหาชื่อ หรือ อีเมล...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>ชื่อ - นามสกุล</th>
                            <th>อีเมล</th>
                            <th>ตำแหน่ง (Role)</th>
                            @if($isAdmin) <th>ร้านสังกัด</th> @endif
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                                        {{ substr($u->name, 0, 1) }}
                                    </div>
                                    <span class="fw-bold">{{ $u->name }}</span>
                                </div>
                            </td>
                            <td>{{ $u->email }}</td>
                            <td>
                                @if($u->role === 'admin') <span class="badge bg-danger">Admin</span>
                                @elseif($u->role === 'shop_owner') <span class="badge bg-success">เจ้าของร้าน</span>
                                @else <span class="badge bg-info text-dark">พนักงาน</span>
                                @endif
                            </td>

                            @if($isAdmin)
                                <td>
                                    @foreach($u->shops as $shop)
                                        <span class="badge bg-light text-dark border">{{ $shop->name }}</span>
                                    @endforeach
                                </td>
                            @endif

                            <td class="text-center">
                                <button wire:click="edit({{ $u->id }})" class="btn btn-sm btn-outline-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:confirm="ต้องการลบผู้ใช้นี้หรือไม่?" wire:click="delete({{ $u->id }})" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 5 : 4 }}" class="text-center py-4 text-muted">ไม่พบข้อมูล</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $users->links() }}</div>
        </div>
    </div>

    <!-- MODAL -->
    <div x-data="{ open: false }"
         x-show="open"
         x-on:show-modal.window="open = true"
         x-on:close-modal.window="open = false"
         x-cloak
         style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 2000;">

        <div class="position-absolute w-100 h-100 bg-dark bg-opacity-50" @click="open = false"></div>

        <div class="position-relative w-100 h-100 d-flex align-items-center justify-content-center pointer-events-none">
            <div class="bg-white rounded shadow-lg w-100 m-3" style="max-width: 500px; pointer-events: auto;">
                <div class="modal-header bg-primary text-white p-3">
                    <h5 class="m-0 fw-bold">{{ $editingId ? 'แก้ไขข้อมูล' : 'เพิ่มผู้ใช้ใหม่' }}</h5>
                    <button type="button" class="btn-close btn-close-white" @click="open = false"></button>
                </div>
                <div class="modal-body p-4">
                    <form wire:submit.prevent="save">

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">ชื่อ - นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control" placeholder="ระบุชื่อ">
                            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">อีเมล (ใช้เข้าระบบ) <span class="text-danger">*</span></label>
                            <input type="email" wire:model="email" class="form-control" placeholder="example@mail.com">
                            @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">รหัสผ่าน {{ $editingId ? '(เว้นว่างถ้าไม่เปลี่ยน)' : '*' }}</label>
                            <input type="password" wire:model="password" class="form-control" placeholder="******">
                            @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">ตำแหน่ง</label>
                            <select wire:model="role" class="form-select">
                                @if($isAdmin)
                                    <option value="admin">ผู้ดูแลระบบ (Super Admin)</option>
                                    <option value="shop_owner">เจ้าของร้าน</option>
                                @endif
                                <option value="staff">พนักงานทั่วไป</option>
                            </select>
                        </div>

                        <!-- Shop Selection (Only for Admin) -->
                        @if($isAdmin)
                        <div class="mb-3">
                            <label class="form-label fw-bold text-primary">สังกัดร้านค้า</label>
                            <select wire:model="shop_id" class="form-select border-primary">
                                <option value="">-- ไม่สังกัดร้าน (Admin Only) --</option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">เลือกเพื่อให้ผู้ใช้นี้เข้าใช้งานร้านค้านั้นๆ ได้</div>
                        </div>
                        @endif

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="button" class="btn btn-secondary px-3" @click="open = false">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary px-4">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
