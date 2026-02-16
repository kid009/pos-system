<div class="container py-5" x-data="{ logoutModalOpen: false }">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">

            <h3 class="mb-4 fw-bold">🏪 กรุณาเลือกร้านที่ต้องการเข้าใช้งาน</h3>

            <!-- ✅ ส่วนพิเศษสำหรับ Admin -->
            @if($isAdmin)
                <div class="card border-primary shadow-sm mb-4">
                    <div class="card-body p-4 bg-primary bg-opacity-10">
                        <h5 class="fw-bold text-primary"><i class="fas fa-user-shield me-2"></i> ผู้ดูแลระบบ (Super Admin)</h5>
                        <p class="small text-muted mb-3">เข้าสู่หน้าจัดการระบบรวมและดูภาพรวมทุกร้านค้า</p>
                        <button wire:click="goToAdminDashboard" class="btn btn-primary w-100 shadow-sm">
                            ไปที่ Admin Dashboard <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                <div class="text-muted small mb-2">- หรือเลือกร้านค้าด้านล่าง -</div>
            @endif

            <!-- รายชื่อร้าน -->
            <div class="list-group shadow-sm">
                @forelse($shops as $shop)
                    <button wire:click="selectShop({{ $shop->id }})"
                            class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center">
                        <div class="text-start">
                            <h5 class="mb-1 fw-bold text-dark">{{ $shop->name }}</h5>
                            <small class="text-muted">
                                <i class="fas fa-user-tag me-1"></i>
                                ตำแหน่ง: {{ ucfirst($shop->pivot->role ?? 'Admin') }}
                            </small>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </button>
                @empty
                    <div class="list-group-item p-5 text-muted">
                        <i class="fas fa-store-slash fa-3x mb-3 opacity-50"></i>
                        <p>คุณยังไม่มีร้านค้าที่สังกัด</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
