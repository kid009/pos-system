<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0"><i class="fas fa-chart-line me-2"></i>Dashboard Overview</h2>
            <p class="text-muted mb-0">Business Snapshot & Analytics</p>
        </div>
        <a href="/pos" class="btn btn-primary fw-bold px-4 py-2 shadow-sm">
            <i class="fas fa-cash-register me-2"></i> Go to POS
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white overflow-hidden">
                <div class="card-body p-3">
                    <h6 class="text-uppercase mb-2 opacity-75 fw-bold font-monospace">Today's Sales</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($todaySales, 2) }}</h3>
                    <small class="opacity-75"><i class="fas fa-calendar-day me-1"></i> {{ date('d M') }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-success text-white overflow-hidden">
                <div class="card-body p-3">
                    <h6 class="text-uppercase mb-2 opacity-75 fw-bold font-monospace">Monthly Sales</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($monthlySales, 2) }}</h3>
                    <small class="opacity-75"><i class="fas fa-calendar-alt me-1"></i> This Month</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-info text-white overflow-hidden" style="background: linear-gradient(45deg, #0dcaf0, #0aa2c0);">
                <div class="card-body p-3">
                    <h6 class="text-uppercase mb-2 opacity-75 fw-bold font-monospace">Net Profit</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($totalProfit, 2) }}</h3>
                    <small class="opacity-75"><i class="fas fa-piggy-bank me-1"></i> All Time</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-indigo text-white overflow-hidden" style="background-color: #6610f2;">
                <div class="card-body p-3">
                    <h6 class="text-uppercase mb-2 opacity-75 fw-bold font-monospace">Avg. Bill (AOV)</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($aov, 2) }}</h3>
                    <small class="opacity-75"><i class="fas fa-receipt me-1"></i> Per Transaction</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-chart-area me-2 text-primary"></i>Sales Trend (30 Days)</h5>
        </div>
        <div class="card-body">
            <div style="height: 300px;">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2 text-muted"></i>Recent Transactions</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Receipt</th>
                                <th>Time</th>
                                <th>Total</th>
                                <th>Method</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $tx)
                            <tr>
                                <td class="ps-3 fw-bold text-primary">{{ $tx->reference_no }}</td>
                                <td class="small text-muted">{{ $tx->created_at->format('d M H:i') }}</td>
                                <td class="fw-bold">{{ number_format($tx->total_amount, 2) }}</td>
                                <td><span class="badge bg-light text-dark border">{{ ucfirst($tx->payment_method) }}</span></td>
                                <td><span class="badge bg-success bg-opacity-10 text-success">{{ $tx->status }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">No data available.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-danger text-white py-2 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert</h6>
                    <span class="badge bg-white text-danger">{{ $lowStockItems->count() }} Items</span>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($lowStockItems as $item)
                    <div class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                        <div>
                            <div class="fw-bold text-dark text-truncate" style="max-width: 200px;">{{ $item->name }}</div>
                            <small class="text-muted">Ref: {{ $item->barcode ?? '-' }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-danger rounded-pill">{{ $item->stock_qty }} left</span>
                        </div>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted small"><i class="fas fa-check-circle text-success me-1"></i> Stock levels are healthy.</div>
                    @endforelse
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-trophy me-2 text-warning"></i>Best Sellers</h5>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($topProducts as $index => $item)
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <span class="badge rounded-pill bg-secondary me-3" style="width: 25px;">{{ $index + 1 }}</span>
                            <span class="fw-bold text-dark">{{ $item->product_name }}</span>
                        </div>
                        <span class="badge bg-primary rounded-pill px-3">{{ number_format($item->total_qty) }} sold</span>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">No sales data yet.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        const ctx = document.getElementById('salesTrendChart').getContext('2d');
        const labels = @json($chartLabels);
        const data = @json($chartData);

        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(13, 110, 253, 0.5)');
        gradient.addColorStop(1, 'rgba(13, 110, 253, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales (THB)',
                    data: data,
                    borderColor: '#0d6efd',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 2] } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
