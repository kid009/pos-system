<div class="container-fluid px-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center my-4">
        <h3 class="fw-bold text-primary">📂 หมวดหมู่ย่อย (Sub Categories)</h3>
        <button wire:click="create" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> เพิ่มรายการ
        </button>
    </div>

    <!-- Content -->
    <div class="card shadow border-0">
        <div class="card-body">

            <div class="mb-3 w-25">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="🔍 ค้นหาหมวดหมู่...">
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>หมวดหมู่หลัก (Main)</th>
                            <th>หมวดหมู่ย่อย (Name)</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                        <tr>
                            <td>
                                @if($cat->mainCategory)
                                    <span class="badge bg-info text-dark">{{ $cat->mainCategory->name }}</span>
                                @else
                                    <span class="text-muted">- ไม่มี -</span>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">{{ $cat->name }}</td>
                            <td class="text-center">
                                <button wire:click="edit({{ $cat->id }})" class="btn btn-sm btn-outline-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    wire:confirm="ลบ '{{ $cat->name }}' ?"
                                    wire:click="delete({{ $cat->id }})"
                                    class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">ไม่พบข้อมูล</td>
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

    <!-- ============================================== -->
    <!-- 🔥 MODAL 🔥 -->
    <!-- ============================================== -->
    <div
        x-data="{ open: false }"
        x-show="open"
        x-on:show-modal.window="open = true"
        x-on:close-modal.window="open = false"
        x-on:keydown.escape.window="open = false"
        x-cloak
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 2000;">

        <div class="position-absolute w-100 h-100 bg-dark bg-opacity-50" @click="open = false"></div>

        <div class="position-relative w-100 h-100 d-flex align-items-center justify-content-center pointer-events-none">
          <div class="bg-white rounded shadow-lg w-100 m-3" style="max-width: 500px; pointer-events: auto;">

            <div class="modal-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
              <h5 class="m-0 fw-bold">
                  {{ $editingId ? '✏️ แก้ไขหมวดหมู่ย่อย' : '➕ เพิ่มหมวดหมู่ย่อย' }}
              </h5>
              <button type="button" class="btn-close btn-close-white" @click="open = false"></button>
            </div>

            <div class="modal-body p-4">
              <form wire:submit.prevent="save">

                <!-- Dropdown เลือกหมวดหมู่หลัก -->
                <div class="mb-3">
                    <label class="form-label fw-bold">หมวดหมู่หลัก</label>
                    <select wire:model="main_category_id" class="form-select">
                        <option value="">-- เลือกหมวดหมู่หลัก (ถ้ามี) --</option>
                        @foreach($mainCategories as $main)
                            <option value="{{ $main->id }}">{{ $main->name }}</option>
                        @endforeach
                    </select>
                    @error('main_category_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                  <label class="form-label fw-bold">ชื่อหมวดหมู่ย่อย <span class="text-danger">*</span></label>
                  <input type="text" wire:model="name" class="form-control" placeholder="เช่น ค่าไฟฟ้า, ค่าเน็ต">
                  @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                  <button type="button" class="btn btn-secondary px-3" @click="open = false">ยกเลิก</button>
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
