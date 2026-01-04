<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>📦 รับสินค้าเข้าสต็อก</h3>

        <button wire:click="openModal" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> รับสินค้าใหม่
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="bg-light">
                    <tr>
                        <th>วันที่</th>
                        <th>ร้านค้า</th>
                        <th>ยอดรวม</th>
                        <th>สถานะ</th>
                        <th>แก้ไข</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $record)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($record->import_date)->format('d/m/Y') }}</td>
                            <td>{{ $record->supplier_name }}</td>
                            <td class="text-end fw-bold">{{ number_format($record->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $record->payment_status_color }}">
                                    {{ $record->payment_type_label }}
                                </span>
                            </td>
                            <td>
                                <button wire:click="edit({{ $record->id }})" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $history->links() }}
        </div>
    </div>

    {{-- Modal for Stock In --}}
    <div x-data="{ open: false }" x-show="open" x-on:show-stock-modal.window="open = true"
        x-on:close-stock-modal.window="open = false" x-cloak style="display: none;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1040;"
            x-transition.opacity></div>

        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1050; overflow-x: hidden; overflow-y: auto; outline: 0;"
            tabindex="-1" class="d-flex justify-content-center align-items-center py-4">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg border-0 rounded-3 overflow-hidden">
                    <div class="modal-header bg-primary text-white p-4">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-file-import me-2"></i>
                            {{ $editingId ? 'แก้ไขใบรับสินค้า (Edit Stock In)' : 'แบบฟอร์มนำเข้าสินค้า (New Stock In)' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                    </div>

                    <div class="modal-body p-4 bg-white">

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">วันที่นำเข้า</label>
                                <input type="date" wire:model="import_date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">ร้านค้า / Supplier</label>
                                <input type="text" wire:model="supplier_name" class="form-control"
                                    placeholder="เช่น แม็คโคร, ปตท.">
                                @error('supplier_name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">วิธีชำระเงิน</label>
                                <select wire:model="payment_type" class="form-select">
                                    <option value="cash">💵 เงินสด (Cash)</option>
                                    <option value="transfer">🏦 โอนเงิน (Transfer)</option>
                                    <option value="credit">📅 ค้างชำระ (Credit)</option>
                                </select>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body p-3 bg-light bg-opacity-50 rounded">

                                <div class="mb-3 position-relative">
                                    <label class="form-label fw-bold h5 mb-3 text-primary"><i
                                            class="fas fa-search me-2"></i>ค้นหาและเพิ่มสินค้า</label>
                                    <div class="input-group input-group-lg">
                                        <input type="text" wire:model.live.debounce.300ms="search_product"
                                            class="form-control border-start-0 ps-0"
                                            placeholder="พิมพ์ชื่อสินค้าเพื่อค้นหา...">
                                    </div>

                                    @if (!empty($products))
                                        <div class="list-group position-absolute w-100 shadow-sm mt-1"
                                            style="z-index: 2000; max-height: 200px; overflow-y: auto;">
                                            @foreach ($products as $prod)
                                                <button type="button" wire:click="addItem({{ $prod->id }})"
                                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                                                    <span class="fw-semibold">{{ $prod->name }}</span>
                                                    <span class="badge bg-secondary rounded-pill">สต็อก:
                                                        {{ $prod->stock_qty }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="table-responsive bg-white rounded shadow-sm border">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light text-secondary small text-uppercase fw-bold">
                                            <tr>
                                                <th class="ps-3">สินค้า</th>
                                                <th width="120" class="text-center">จำนวน</th>
                                                <th width="140" class="text-end">ต้นทุน/หน่วย</th>
                                                <th width="140" class="text-end">รวม</th>
                                                <th width="60" class="text-center">ลบ</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-top-0">
                                            @foreach ($items as $index => $item)
                                                <tr wire:key="item-{{ $index }}">
                                                    <td class="ps-3 fw-semibold text-dark">{{ $item['name'] }}</td>
                                                    <td>
                                                        <input type="number"
                                                            wire:model.live.debounce.300ms="items.{{ $index }}.qty"
                                                            class="form-control text-center fw-bold text-primary"
                                                            min="1">
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            wire:model.live.debounce.300ms="items.{{ $index }}.unit_cost"
                                                            class="form-control text-end" step="0.01">
                                                    </td>
                                                    <td class="text-end fw-bold text-dark pe-3">
                                                        {{ number_format($item['qty'] * $item['unit_cost'], 2) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <button wire:click="removeItem({{ $index }})"
                                                            class="btn btn-link text-danger p-0 hover-scale"
                                                            title="ลบรายการ">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-light">
                                            <tr>
                                                <td colspan="3" class="text-end align-middle py-3">
                                                    <span class="fw-bold text-secondary">ยอดรวมสุทธิ (Grand
                                                        Total):</span>
                                                </td>
                                                <td class="text-end align-middle py-3">
                                                    <span class="h4 fw-bold text-primary mb-0">
                                                        {{ number_format($this->grandTotal, 2) }}
                                                    </span>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    @if (count($items) === 0)
                                        <div class="text-center p-5 text-muted">
                                            <i class="fas fa-shopping-basket fa-3x mb-3 text-gray-300"></i>
                                            <p class="mb-0">ยังไม่มีสินค้าในรายการ</p>
                                            <small>กรุณาค้นหาและเลือกสินค้าด้านบน</small>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="modal-footer bg-light p-3">
                        <button type="button" wire:click="closeModal"
                            class="btn btn-outline-secondary px-4 hover-scale">
                            <i class="fas fa-times me-1"></i> ยกเลิก
                        </button>
                        <button type="button" wire:click="save" class="btn btn-success px-4 hover-scale" {{ count($items) === 0 ? 'disabled' : '' }}>
                            <i class="fas fa-save me-1"></i>
                            {{ $editingId ? 'บันทึกการแก้ไข' : 'บันทึกข้อมูล' }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
