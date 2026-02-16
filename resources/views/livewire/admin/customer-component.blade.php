<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">รายชื่อลูกค้า</h1>
        <button wire:click="create" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> เพิ่มลูกค้า
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="input-group">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control bg-light border-0 small" placeholder="ค้นหาด้วยชื่อ หรือ เบอร์โทรศัพท์" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ชื่อลูกค้า</th>
                            <th>เบอร์โทรศัพท์</th>
                            <th>คะแนนสะสม</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone ?? '-' }}</td>
                            <td><span class="badge bg-success">{{ number_format($customer->points) }}</span></td>
                            <td class="text-center">
                                <button wire:click="edit({{ $customer->id }})" class="btn btn-sm btn-info text-white me-1">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button type="button"
                                        class="btn btn-sm btn-danger"
                                        @click="$dispatch('open-confirm-modal', {
                                            component: '{{ $this->getId() }}',
                                            method: 'delete',
                                            params: {{ $customer->id }},
                                            title: 'Delete Customer?',
                                            message: 'Are you sure you want to delete {{ $customer->name }}?'
                                        })">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">ไม่มีข้อมูล</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $customers->links() }}
            </div>
        </div>
    </div>

    @if($isOpen)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $customerId ? 'Edit Customer' : 'Add Customer' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="{{ $customerId ? 'update' : 'store' }}">

                        <div class="mb-3">
                            <label class="form-label">ชื่อลูกค้า <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">เบอร์โทรศัพท์ <label>
                            <input type="text" wire:model="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="08XXXXXXXX">
                            @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">คะแนนสะสม</label>
                            <input type="number" wire:model="points" class="form-control @error('points') is-invalid @enderror">
                            @error('points') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="text-end">
                            <button type="button" wire:click="closeModal" class="btn btn-secondary">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
