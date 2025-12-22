<div class="container-fluid py-4"> <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Sales Report</h2>
            <p class="text-muted mb-0">ตรวจสอบและเรียกดูยอดขายย้อนหลัง</p>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            {{-- <button onclick="window.print()" class="btn btn-primary fw-bold shadow-sm">
                <i class="fas fa-print me-2"></i> Print / Export PDF
            </button> --}}
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light p-4 rounded">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="fw-bold mb-1 text-muted">Start Date (จากวันที่)</label>
                    <input type="date" wire:model.live="start_date" class="form-control form-control-lg shadow-sm">
                </div>
                <div class="col-md-4">
                    <label class="fw-bold mb-1 text-muted">End Date (ถึงวันที่)</label>
                    <input type="date" wire:model.live="end_date" class="form-control form-control-lg shadow-sm">
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="p-2 bg-white rounded shadow-sm border d-inline-block px-4">
                        <small class="text-muted d-block text-uppercase fw-bold">Total Sales in Period</small>
                        <h3 class="mb-0 text-success fw-bold">{{ number_format($totalSales, 2) }} ฿</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="py-3 ps-4 text-start">Date / Time</th>
                            <th class="py-3">Receipt No.</th>
                            <th class="py-3">Cashier (User)</th>
                            <th class="py-3">Customer</th>
                            <th class="py-3">Payment</th>
                            <th class="py-3 text-end pe-4">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $tx)
                        <tr>
                            <td class="ps-4 text-start text-muted">
                                {{ $tx->created_at->format('d M Y') }} <br>
                                <small>{{ $tx->created_at->format('H:i:s') }}</small>
                            </td>
                            <td class="fw-bold text-primary">{{ $tx->reference_no }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="fas fa-user-tag me-1"></i> {{ $tx->user->name ?? 'Unknown' }}
                                </span>
                            </td>
                            <td>
                                @if($tx->customer)
                                    <span class="text-dark fw-bold">{{ $tx->customer->name }}</span>
                                @else
                                    <span class="text-muted small">- General -</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-white border text-dark text-uppercase">
                                    {{ $tx->payment_method }}
                                </span>
                            </td>
                            <td class="text-end pe-4 fw-bold fs-5 text-success">
                                {{ number_format($tx->total_amount, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-search fa-3x mb-3 opacity-25"></i> <br>
                                ไม่พบรายการขายในช่วงเวลาที่เลือก
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="5" class="text-end py-3 text-uppercase text-muted">Total Sum:</td>
                            <td class="text-end pe-4 py-3 fs-4 text-success">{{ number_format($totalSales, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .btn, .card-header, input, label, a { display: none !important; }
            .card { border: none !important; box-shadow: none !important; }
            .table-responsive { overflow: visible !important; }
            /* ซ่อน Sidebar และ Navbar ของ Layout หลักด้วย (ถ้ามี) */
            nav, aside, header { display: none !important; }
        }
    </style>

</div>

