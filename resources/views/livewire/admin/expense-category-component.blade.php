<div class="container-fluid px-4">
  <div class="d-flex justify-content-between align-items-center my-4">
    <h2 class="fw-bold text-primary">📂 หมวดหมู่รายจ่าย</h2>
    <button wire:click="openCreateModal" class="btn btn-primary shadow-sm">
      <i class="fas fa-plus-circle me-1"></i> เพิ่มหมวดหมู่ใหม่
    </button>
  </div>

  <div class="card shadow border-0 mb-4">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="bg-light">
            <tr>
              <th scope="col" style="width: 50%;">รายการ (Name)</th>
              <th scope="col" style="width: 30%;">หมวดหมู่ (Group)</th>
              <th scope="col" class="text-center">จัดการ</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($expenseCategories as $item)
              <tr>
                <td class="fw-bold text-dark">{{ $item->name }}</td>
                <td>
                  <span class="badge bg-info text-dark">{{ $item->group }}</span>
                </td>
                <td class="text-center">
                  <button class="btn btn-sm btn-outline-warning disabled">แก้ไข</button>
                  <button class="btn btn-sm btn-outline-danger disabled">ลบ</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="mt-3">
        {{ $expenseCategories->links() }}
      </div>
    </div>
  </div>

  <div
    x-data="{ open: false }"
    x-show="open"
    x-on:show-expense-category-modal.window="open = true"
    x-on:close-expense-category-modal.window="open = false"
    x-on:keydown.escape.window="open = false"
    x-cloak
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 2000;">

    <div class="position-absolute w-100 h-100 bg-dark bg-opacity-50" @click="open = false"></div>

    <div class="position-relative w-100 h-100 d-flex align-items-center justify-content-center pointer-events-none">

      <div class="bg-white rounded shadow-lg w-100 m-3" style="max-width: 500px; pointer-events: auto;">

        <div class="modal-header bg-info text-white p-3 d-flex justify-content-between align-items-center">
          <h5 class="m-0 fw-bold">🧾 เพิ่มหมวดหมู่รายจ่าย</h5>
          <button type="button" class="btn-close btn-close-white" @click="open = false"></button>
        </div>

        <div class="modal-body p-4">
          <form wire:submit.prevent="store">

            <div class="mb-3">
              <label class="form-label fw-bold">ชื่อรายการ <span class="text-danger">*</span></label>
              <input type="text" wire:model="name" class="form-control" placeholder="เช่น ค่าเช่าสำนักงาน">
              @error('name')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4"> <label class="form-label fw-bold">อยู่ในหมวดหมู่ <span
                  class="text-danger">*</span></label>
              <input type="text" wire:model="group" class="form-control" placeholder="เช่น ค่าใช้จ่ายคงที่">
              @error('group')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
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
