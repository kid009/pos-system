<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h3 class="text-primary fw-bold"><i class="fas fa-history me-2"></i>ประวัติการขาย</h3>
        <div class="d-flex gap-2">
            <input type="date" wire:model.live="filter_date" class="form-control" style="width: 150px;">
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="ค้นหาเลขบิล..." style="width: 250px;">
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">เวลา</th>
                            <th>เลขที่บิล</th>
                            <th>ลูกค้า</th>
                            <th class="text-end">ยอดรวม</th>
                            <th class="text-center">วิธีชำระ</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $item)
                        <tr wire:key="row-{{ $item->id }}">
                            <td class="ps-3 small text-muted">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td class="fw-bold text-primary">{{ $item->reference_no }}</td>
                            <td>{{ $item->customer->name ?? '-' }}</td>
                            <td class="text-end fw-bold">{{ number_format($item->total_amount, 2) }}</td>
                            <td class="text-center">
                                @switch($item->payment_method)
                                    @case('cash') <span class="badge bg-success">เงินสด</span> @break
                                    @case('transfer') <span class="badge bg-info text-dark">โอนเงิน</span> @break
                                    @case('unpaid') <span class="badge bg-danger">ค้างชำระ</span> @break
                                    @case('half_half') <span class="badge bg-warning text-dark">คนละครึ่ง</span> @break
                                @endswitch
                            </td>
                            <td class="text-center">
                                @if($item->status == 'completed')
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <span class="badge bg-secondary">ยกเลิก</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button wire:click="openViewModal({{ $item->id }})" class="btn btn-sm btn-outline-info me-1">
                                    <i class="fas fa-list"></i>
                                </button>
                                <button wire:click="openEditModal({{ $item->id }})" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">ไม่พบข้อมูล</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="p-3">
            {{ $transactions->links() }}
        </div>
    </div>

    <div
        x-data="{ open: false }"
        x-show="open"
        x-on:show-view-modal.window="open = true"
        x-on:close-view-modal.window="open = false"
        x-cloak
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 2000;"
    >
        <div class="position-absolute w-100 h-100 bg-dark bg-opacity-50" @click="open = false"></div>

        <div class="position-relative w-100 h-100 d-flex align-items-center justify-content-center pointer-events-none">
            <div class="bg-white rounded shadow-lg w-100 m-3 overflow-hidden" style="max-width: 700px; pointer-events: auto;">
                <div class="modal-header bg-info text-white p-3 d-flex justify-content-between">
                    <h5 class="m-0">🧾 รายละเอียดบิล</h5>
                    <button type="button" class="btn-close btn-close-white" @click="open = false"></button>
                </div>
                <div class="modal-body p-0 table-responsive" style="max-height: 60vh; overflow-y: auto;">
                    <table class="table table-striped mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="ps-4">สินค้า</th>
                                <th class="text-center">ราคา</th>
                                <th class="text-center">จำนวน</th>
                                <th class="text-end pe-4">รวม</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($viewDetails)
                                @foreach($viewDetails as $d)
                                <tr>
                                    <td class="ps-4">{{ $d->product_name }}</td>
                                    <td class="text-center">{{ number_format($d->price, 2) }}</td>
                                    <td class="text-center">{{ $d->quantity }}</td>
                                    <td class="text-end pe-4">{{ number_format($d->total_price, 2) }}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer bg-light p-3 text-end">
                    <span class="me-3 fw-bold text-secondary">ยอดรวม:</span>
                    <span class="h4 text-primary fw-bold mb-0">
                        {{ number_format($viewTransaction->total_amount ?? 0, 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div
        x-data="{ open: false }"
        x-show="open"
        x-on:show-edit-modal.window="open = true"
        x-on:close-edit-modal.window="open = false"
        x-cloak
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 2100;"
    >
        <div class="position-absolute w-100 h-100 bg-dark bg-opacity-50" @click="open = false"></div>

        <div class="position-relative w-100 h-100 d-flex align-items-center justify-content-center pointer-events-none">
            <div class="bg-white rounded shadow-lg w-100 m-3" style="max-width: 500px; pointer-events: auto;">

                <div class="modal-header bg-warning p-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-dark"><i class="fas fa-edit me-2"></i>แก้ไขการชำระเงิน</h5>
                    <button type="button" class="btn-close" @click="open = false"></button>
                </div>

                <div class="modal-body p-4">
                    <div wire:loading wire:target="openEditModal" class="w-100 text-center py-4">
                        <div class="spinner-border text-warning" role="status"></div>
                        <p class="mt-2 text-muted">กำลังโหลดข้อมูล...</p>
                    </div>

                    <div wire:loading.remove wire:target="openEditModal">

                        <div class="mb-3">
                            <label class="form-label fw-bold">ลูกค้า</label>
                            <select wire:model="edit_customer_id" class="form-select">
                                <option value="">-- ลูกค้าทั่วไป --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">วิธีชำระเงิน</label>
                            <select wire:model="edit_payment_method" class="form-select">
                                <option value="cash">เงินสด</option>
                                <option value="transfer">โอนเงิน</option>
                                <option value="unpaid">ค้างชำระ</option>
                                <option value="half_half">คนละครึ่ง</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">ยอดเงินที่รับมา</label>
                            <input type="number" step="0.01" wire:model="edit_received_amount" class="form-control text-end fs-4 fw-bold text-success">
                            <div class="form-text">ยอดบิลคือ: {{ number_format($viewTransaction->total_amount ?? 0, 2) }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">สถานะบิล</label>
                            <select wire:model="edit_status" class="form-select">
                                <option value="completed">✅ ปกติ</option>
                                <option value="void">❌ ยกเลิกบิล</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary" @click="open = false">ยกเลิก</button>
                    <button type="button" wire:click="updateTransaction" class="btn btn-primary">
                        <span wire:loading.remove wire:target="updateTransaction">บันทึก</span>
                        <span wire:loading wire:target="updateTransaction">กำลังบันทึก...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
