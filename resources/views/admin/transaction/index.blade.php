@extends('layouts.app')

@section('title', 'ประวัติการขาย')

@section('content')
    <div class="pt-3 pb-2 mb-3">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-4">
            <h1 class="h2 mb-0">ประวัติการขาย (Sales History)</h1>

            <a href="{{ route('pos.index') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                <span data-feather="monitor"></span> ไปหน้าจอขาย POS
            </a>
        </div>

        <div class="card shadow-sm border-0 mb-4 bg-light">
            <div class="card-body">
                <form action="{{ route('transactions.index') }}" method="GET" class="row g-3 align-items-end">

                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">ค้นหาเลขที่บิล</label>
                        <input type="text" name="search" class="form-control" placeholder="เช่น INV2026..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">ตั้งแต่วันที่</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">ถึงวันที่</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>

                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit"
                                class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-2">
                                <span data-feather="filter"></span> กรองข้อมูล
                            </button>
                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary"
                                title="ล้างการค้นหา">
                                <span data-feather="refresh-ccw"></span>
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="py-3 px-3">วัน</th>
                                <th class="py-3">เลขที่ใบเสร็จ</th>
                                @if (auth()->user()->role === 'admin')
                                    <th class="py-3">สาขา</th>
                                @endif
                                <th class="py-3">ลูกค้า</th>
                                <th class="py-3">พนักงาน</th>
                                <th class="py-3 text-end">ยอดรวมสุทธิ</th>
                                <th class="py-3 text-center">ชำระเงิน</th>
                                <th class="py-3 text-center">สถานะ</th>
                                <th class="py-3 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $tx)
                                <tr>
                                    <td class="px-3">
                                        <div class="fw-bold">{{ $tx->transaction_date ? $tx->transaction_date->format('d/m/Y') : '-' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fw-bold px-2 py-1 fs-6">
                                            {{ $tx->invoice_no }}
                                        </span>
                                    </td>

                                    @if (auth()->user()->role === 'admin')
                                        <td>{{ $tx->shop->name ?? '-' }}</td>
                                    @endif

                                    <td>{{ $tx->customer->name ?? 'ลูกค้าทั่วไป' }}</td>

                                    <td>{{ $tx->cashier->name ?? '-' }}</td>

                                    <td class="text-end fw-bold text-success fs-5">
                                        {{ number_format($tx->total_amount, 2) }}
                                    </td>

                                    <td class="text-center">
                                        @if($tx->payment_method === 'cash')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">เงินสด</span>
                                        @elseif($tx->payment_method === 'transfer')
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">โอนเงิน</span>
                                        @elseif($tx->payment_method === 'credit')
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">ค้างจ่าย</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">{{ $tx->payment_method }}</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if ($tx->status === 'completed')
                                            <span class="badge bg-success rounded-pill px-3 py-1 fw-normal">สำเร็จ</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill px-3 py-1 fw-normal">ยกเลิก</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('transactions.show', $tx->id) }}" class="btn btn-sm btn-outline-primary" title="ดูรายละเอียด">
                                            <span data-feather="eye" style="width: 14px; height: 14px;"></span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role === 'admin' ? 9 : 8 }}"
                                        class="text-center py-5 text-muted">
                                        <span data-feather="inbox" style="width: 48px; height: 48px; opacity: 0.5"></span>
                                        <h5 class="mt-3">ไม่พบข้อมูลประวัติการขาย</h5>
                                        <p>ลองเปลี่ยนช่วงวันที่ค้นหาใหม่อีกครั้ง</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($transactions->hasPages())
                <div class="card-footer bg-white py-3 border-0 d-flex justify-content-end">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
