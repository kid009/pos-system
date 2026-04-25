@extends('layouts.app')

@section('title', 'จัดการบริษัทขนส่ง')

@section('content')
<div class="pt-3 pb-2 mb-3">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-4">
        <h1 class="h2 mb-0">บริษัทขนส่ง (Shipping Method)</h1>

        <div class="d-flex gap-2">
            <form action="{{ route('shipping-methods.index') }}" method="GET" class="d-flex">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อขนส่ง..." value="{{ $search }}">
                    <button class="btn btn-outline-secondary" type="submit"><span data-feather="search" style="width: 14px;"></span></button>
                </div>
            </form>
            <a href="{{ route('shipping-methods.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                <span data-feather="plus"></span>
                <span class="d-none d-sm-inline">เพิ่มขนส่ง</span>
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 text-center" style="width: 80px;">ลำดับ</th>
                            <th class="py-3">ชื่อบริษัทขนส่ง</th>
                            <th class="py-3 text-center">สถานะ</th>
                            <th class="py-3 text-center" style="width: 120px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shippingMethods as $index => $shippingMethod)
                        <tr>
                            <td class="px-4 py-3 text-center">{{ $shippingMethods->firstItem() + $index }}</td>
                            <td class="fw-bold text-dark">{{ $shippingMethod->name }}</td>
                            <td class="text-center">
                                @if($shippingMethod->is_active)
                                    <span class="badge bg-success rounded-pill px-3 py-2 fw-normal">ใช้งาน</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-normal">ปิดใช้งาน</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('shipping-methods.edit', $shippingMethod->id) }}" class="btn btn-sm btn-outline-warning" title="แก้ไข">
                                        <span data-feather="edit" style="width: 14px; height: 14px;"></span>
                                    </a>
                                    <form action="{{ route('shipping-methods.destroy', $shippingMethod->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบบริษัทขนส่งนี้?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบ">
                                            <span data-feather="trash-2" style="width: 14px; height: 14px;"></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <span data-feather="truck" style="width: 48px; height: 48px;" class="mb-2 text-secondary"></span>
                                <h5>ยังไม่มีข้อมูลบริษัทขนส่ง</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($shippingMethods->hasPages())
        <div class="card-footer bg-white py-3 border-0 d-flex justify-content-end">
             {{ $shippingMethods->links() }}
        </div>
        @endif
    </div>
</div>
@endsection