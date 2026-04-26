@extends('layouts.app')

@section('title', 'จัดการสินค้า')

@section('content')
<div class="pt-3 pb-2 mb-3">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-4">
        <h1 class="h2 mb-0">สินค้า (Product)</h1>

        <div class="d-flex gap-2">
            <form action="{{ route('master-products.index') }}" method="GET" class="d-flex">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อ/รหัสสินค้า..." value="{{ $search }}">
                    <button class="btn btn-outline-secondary" type="submit"><span data-feather="search" style="width: 14px;"></span></button>
                </div>
            </form>
            <a href="{{ route('master-products.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                <span data-feather="plus"></span>
                <span class="d-none d-sm-inline">เพิ่มสินค้า</span>
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
                            <th class="py-3">รูป</th>
                            <th class="py-3">รหัสสินค้า</th>
                            <th class="py-3">ชื่อสินค้า</th>
                            <th class="py-3">หมวดหมู่</th>
                            <th class="py-3 text-end">ราคา</th>
                            <th class="py-3 text-center">สต็อก</th>
                            <th class="py-3 text-center">สถานะ</th>
                            <th class="py-3 text-center" style="width: 120px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                        <tr>
                            <td class="px-4 py-3 text-center">{{ $products->firstItem() + $index }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span data-feather="image" class="text-white" style="width: 20px; height: 20px;"></span>
                                    </div>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary rounded-pill px-2 fw-normal">{{ $product->sku }}</span></td>
                            <td class="fw-bold text-dark">{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td class="text-end">{{ number_format($product->price, 2) }}</td>
                            <td class="text-center">{{ $product->stock_qty }}</td>
                            <td class="text-center">
                                @if($product->is_active)
                                    <span class="badge bg-success rounded-pill px-3 py-2 fw-normal">ใช้งาน</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-normal">ปิดใช้งาน</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('master-products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning" title="แก้ไข">
                                        <span data-feather="edit" style="width: 14px; height: 14px;"></span>
                                    </a>
                                    <form action="{{ route('master-products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบสินค้านี้?');">
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
                            <td colspan="9" class="text-center text-muted py-5">
                                <span data-feather="box" style="width: 48px; height: 48px;" class="mb-2 text-secondary"></span>
                                <h5>ยังไม่มีข้อมูลสินค้า</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($products->hasPages())
        <div class="card-footer bg-white py-3 border-0 d-flex justify-content-end">
             {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection