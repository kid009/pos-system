<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark"><i class="fas fa-globe-asia me-2 text-primary"></i>System Overview</h2>
            <p class="text-muted mb-0">ภาพรวมระบบทั้งหมด (Global Admin)</p>
        </div>
        <div>
            <a href="{{ route('select-shop') }}" class="btn btn-outline-secondary">
                <i class="fas fa-store me-1"></i> เปลี่ยนโหมดเข้าร้านค้า
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white-50 small text-uppercase fw-bold">Total Shops</div>
                            <div class="h2 fw-bold mb-0">{{ $totalShops }}</div>
                        </div>
                        <i class="fas fa-store fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white-50 small text-uppercase fw-bold">Revenue (Month)</div>
                            <div class="h2 fw-bold mb-0">{{ number_format($totalRevenueMonth) }}</div>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-white-50 small text-uppercase fw-bold">Total Users</div>
                            <div class="h2 fw-bold mb-0">{{ $totalUsers }}</div>
                        </div>
                        <i class="fas fa-users fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-dark-50 small text-uppercase fw-bold">Transactions</div>
                            <div class="h2 fw-bold mb-0">{{ number_format($totalTransactions) }}</div>
                        </div>
                        <i class="fas fa-receipt fa-2x text-dark-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row g-4">
        <!-- Top Performing Shops -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-trophy me-2"></i>Top Performing Shops (Revenue)</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Shop Name</th>
                                <th class="text-end pe-4">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topShops as $shop)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $shop->name }}</div>
                                        <small class="text-muted">{{ $shop->address }}</small>
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="badge bg-success bg-opacity-10 text-success fs-6">
                                            ฿{{ number_format($shop->transactions_sum_total_amount, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center py-4 text-muted">No data available</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Shops Created -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-info"><i class="fas fa-clock me-2"></i>Recently Added Shops</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Shop Name</th>
                                <th>Phone</th>
                                <th class="text-center">Users</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentShops as $shop)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $shop->name }}</td>
                                    <td>{{ $shop->phone ?? '-' }}</td>
                                    <td class="text-center"><span class="badge bg-secondary rounded-pill">{{ $shop->users_count }}</span></td>
                                    <td class="small text-muted">{{ $shop->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">No shops found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
