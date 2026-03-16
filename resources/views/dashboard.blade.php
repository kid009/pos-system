@extends('layouts.app')

@section('title', 'ภาพรวมระบบ - POS System')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h2">ภาพรวมระบบ (Dashboard)</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">ส่งออก (Export)</button>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary">
                <span data-feather="calendar"></span>
                วันนี้: {{ date('d/m/Y') }}
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-subtitle text-white-50">ยอดขายวันนี้</h6>
                        <span data-feather="dollar-sign"></span>
                    </div>
                    <h3 class="card-title mb-0">฿{{ number_format($todaySales, 2) }}</h3>
                    <p class="card-text small mt-2">{{ $todayTransactionsCount }} รายการ</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-subtitle text-white-50">ยอดขายเดือนนี้</h6>
                        <span data-feather="trending-up"></span>
                    </div>
                    <h3 class="card-title mb-0">฿{{ number_format($monthSales, 2) }}</h3>
                    <p class="card-text small mt-2">อัปเดตล่าสุด: {{ date('H:i') }} น.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-subtitle text-dark-50">สินค้าในระบบ</h6>
                        <span data-feather="box"></span>
                    </div>
                    <h3 class="card-title mb-0">{{ number_format($productsCount) }}</h3>
                    <p class="card-text small mt-2">รายการทั้งหมด</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-subtitle text-white-50">สาขาที่ดูแล</h6>
                        <span data-feather="map-pin"></span>
                    </div>
                    <h3 class="card-title mb-0">
                        {{ auth()->user()->role === 'admin' ? 'ทุกสาขา' : auth()->user()->shop->name ?? '1 สาขา' }}</h3>
                    <p class="card-text small mt-2">สถานะ: ปกติ</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold">แนวโน้มยอดขาย (7 วันล่าสุด)</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="280"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold">สินค้าขายดี</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="small fw-bold">สินค้า</th>
                                    <th class="small fw-bold text-center">จำนวน</th>
                                    <th class="small fw-bold text-end pe-3">ยอดรวม</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $product)
                                    <tr>
                                        <td class="small">{{ $product->product_name }}</td>
                                        <td class="small text-center fw-bold">{{ $product->total_qty }}</td>
                                        <td class="small text-end pe-3">฿{{ number_format($product->total_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted small">ไม่มีข้อมูลสินค้าขายดี
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold">รายการขายล่าสุด</h5>
            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-link text-decoration-none">ดูทั้งหมด</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="ps-3 py-3 small">วัน-เวลา</th>
                            <th class="py-3 small">เลขที่ใบเสร็จ</th>
                            <th class="py-3 small">พนักงาน</th>
                            <th class="py-3 small text-end">ยอดรวม</th>
                            <th class="py-3 small text-center">สถานะ</th>
                            <th class="py-3 small text-center pe-3">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $tx)
                            <tr>
                                <td class="ps-3 small">
                                    <div class="fw-bold">
                                        {{ \Carbon\Carbon::parse($tx->transaction_date)->format('d/m/Y') }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ \Carbon\Carbon::parse($tx->transaction_date)->format('H:i') }} น.</div>
                                </td>
                                <td class="small fw-bold">{{ $tx->invoice_no }}</td>
                                <td class="small">{{ $tx->cashier->name ?? '-' }}</td>
                                <td class="small text-end fw-bold text-primary">฿{{ number_format($tx->total_amount, 2) }}
                                </td>
                                <td class="small text-center">
                                    <span class="badge bg-success rounded-pill px-2 py-1 fw-normal">สำเร็จ</span>
                                </td>
                                <td class="small text-center pe-3">
                                    <a href="{{ route('transactions.show', $tx->id) }}"
                                        class="btn btn-sm btn-outline-primary px-2 py-0">
                                        <span data-feather="eye" style="width: 12px; height: 12px;"></span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">ไม่พบข้อมูลรายการขายล่าสุด</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const chartData = @json($chartData);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.map(item => item.date),
                    datasets: [{
                        label: 'ยอดขาย (บาท)',
                        data: chartData.map(item => item.amount),
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#0d6efd',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '฿' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
