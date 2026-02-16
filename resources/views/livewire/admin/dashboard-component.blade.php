<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary fw-bold"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h3>
        <span class="text-muted">Overview for {{ date('F Y') }}</span>
    </div>

    <!-- ROW 1: Financial Cards -->
    <div class="row g-3 mb-4">
        <!-- ยอดขายวันนี้ -->
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-primary border-4 h-100">
                <div class="card-body">
                    <div class="text-uppercase small fw-bold text-primary mb-1">ยอดขายวันนี้</div>
                    <div class="h3 mb-0 fw-bold text-gray-800">{{ number_format($todaySales, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- ยอดขายเดือนนี้ -->
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-info border-4 h-100">
                <div class="card-body">
                    <div class="text-uppercase small fw-bold text-info mb-1">ยอดขายรายเดือน</div>
                    <div class="h3 mb-0 fw-bold text-gray-800">{{ number_format($monthlySales, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- ✅ กำไรขั้นต้น (Gross Profit) -->
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-success border-4 h-100">
                <div class="card-body">
                    <div class="text-uppercase small fw-bold text-success mb-1">กำไรขั้นต้น</div>
                    <div class="h3 mb-0 fw-bold text-gray-800">
                        {{ number_format($totalProfit, 2) }}
                        <small class="fs-6 text-muted font-weight-normal">บาท</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ ลูกหนี้ค้างชำระ (Account Receivable) -->
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-danger border-4 h-100">
                <div class="card-body">
                    <div class="text-uppercase small fw-bold text-danger mb-1">ยอดค้างชำระ</div>
                    <div class="h3 mb-0 fw-bold text-danger">
                        {{ number_format($accountReceivable, 2) }}
                        <small class="fs-6 text-muted font-weight-normal">บาท</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 2: Cylinder Stats & Charts -->
    <div class="row g-3 mb-4">

        <!-- ✅ รายงานการเคลื่อนไหวถังแก๊ส -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-gas-pump me-2"></i>สถิติการเคลื่อนไหวถังแก๊ส (เดือน)</h6>
                </div>
                <div class="card-body">
                    @if(count($cylinderStats) > 0)
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-sync text-info me-2"></i> หมุนเวียน (Refill)</span>
                                <span class="badge bg-info rounded-pill fs-6">{{ number_format($cylinderStats['refill'] ?? 0) }} ถัง</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-plus-circle text-success me-2"></i> ขายถังใหม่ (New)</span>
                                <span class="badge bg-success rounded-pill fs-6">{{ number_format($cylinderStats['new'] ?? 0) }} ถัง</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-box text-warning me-2"></i> ฝากเติม (Deposit)</span>
                                <span class="badge bg-warning text-dark rounded-pill fs-6">{{ number_format($cylinderStats['deposit'] ?? 0) }} ถัง</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-handshake text-secondary me-2"></i> ยืมถัง (Borrow)</span>
                                <span class="badge bg-secondary rounded-pill fs-6">{{ number_format($cylinderStats['borrow'] ?? 0) }} ถัง</span>
                            </li>
                        </ul>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-gas-pump fa-2x mb-2 opacity-50"></i>
                            <p>ไม่มีข้อมูลการขายแก๊สในเดือนนี้</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-chart-area me-2"></i>แนวโน้มยอดขาย (30 วัน)</h6>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 3: Tables (Top Products & Low Stock) -->
    <div class="row g-3">
        <!-- Top Products -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-success"><i class="fas fa-thumbs-up me-2"></i>สินค้าขายดี 5 อันดับ</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">สินค้า</th>
                                <th class="text-end pe-3">จำนวนที่ขาย</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $top)
                            <tr>
                                <td class="ps-3">{{ $top->product_name }}</td>
                                <td class="text-end pe-3 fw-bold">{{ $top->total_qty }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-3 text-muted">ไม่พบข้อมูลการขาย</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>แจ้งเตือนสินค้าเหลือน้อย</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">สินค้า</th>
                                <th class="text-center">คงเหลือ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockItems as $low)
                            <tr>
                                <td class="ps-3">{{ $low->name }}</td>
                                <td class="text-center fw-bold text-danger">{{ $low->stock_qty }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-3 text-success">
                                    <i class="fas fa-check-circle me-1"></i> ระดับสต็อกเพียงพอ!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Script (วางไว้ล่างสุดและใช้ Event Listener เพื่อความชัวร์) -->
    <script>
        document.addEventListener('livewire:initialized', () => {

            // เช็คว่า Chart.js โหลดมาหรือยัง ถ้ายังไม่โหลดให้ข้ามไปก่อน
            if (typeof Chart === 'undefined') {
                console.warn('Chart.js is not loaded.');
                return;
            }

            const ctx = document.getElementById('salesChart');

            if (ctx) {
                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Sales (Baht)',
                            data: @json($chartData),
                            borderColor: '#4e73df',
                            backgroundColor: 'rgba(78, 115, 223, 0.05)',
                            borderWidth: 2,
                            tension: 0.3,
                            pointRadius: 2,
                            pointBackgroundColor: '#4e73df',
                            pointBorderColor: '#fff',
                            pointHoverRadius: 5,
                            fill: true
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { borderDash: [2, 4], color: "#eaecf4" },
                                ticks: { callback: function(value) { return '฿' + value.toLocaleString(); } }
                            },
                            x: { grid: { display: false } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            }
        });
    </script>
</div>
