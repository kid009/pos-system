@extends('layouts.app')

@section('title', 'จัดการร้านค้า')

@section('content')
<div class="pt-3 pb-2 mb-3">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-4">
        <h1 class="h2 mb-0">จัดการร้านค้า (Shops)</h1>

        <a href="{{ route('shop.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
            <span data-feather="plus"></span>
            <span class="d-none d-sm-inline">เพิ่มร้านค้าใหม่</span>
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">ชื่อร้าน</th>
                            <th class="py-3 d-none d-md-table-cell">รหัสสาขา</th>
                            <th class="py-3 d-none d-sm-table-cell">เบอร์โทร</th>
                            <th class="py-3 text-center">สถานะ</th>
                            <th class="py-3 text-center" style="width: 120px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shops as $shop)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-dark">{{ $shop->name }}</div>
                                @if($shop->tax_id)
                                    <div class="text-muted small">TAX: {{ $shop->tax_id }}</div>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">{{ $shop->branch_code ?? '-' }}</td>
                            <td class="d-none d-sm-table-cell">{{ $shop->phone ?? '-' }}</td>
                            <td class="text-center">
                                @if($shop->is_active)
                                    <span class="badge bg-success rounded-pill px-3 py-2 fw-normal">ใช้งาน</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-normal">ปิดใช้งาน</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('shop.edit', $shop->id) }}" class="btn btn-sm btn-outline-warning d-flex align-items-center" title="แก้ไข">
                                        <span data-feather="edit" style="width: 14px; height: 14px;"></span>
                                    </a>

                                    <form action="{{ route('shop.destroy', $shop->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบข้อมูลร้านค้านี้?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center" title="ลบ">
                                            <span data-feather="trash-2" style="width: 14px; height: 14px;"></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <div class="mb-2 text-secondary">
                                    <span data-feather="inbox" style="width: 48px; height: 48px;"></span>
                                </div>
                                <h5>ไม่พบข้อมูลร้านค้า</h5>
                                <p class="small">คลิกที่ปุ่ม "เพิ่มร้านค้าใหม่" ด้านบนเพื่อเริ่มต้นใช้งาน</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($shops->hasPages())
        <div class="card-footer bg-white d-flex justify-content-end py-3 border-0">
             {{ $shops->links() }} </div>
        @endif
    </div>
</div>
@endsection
