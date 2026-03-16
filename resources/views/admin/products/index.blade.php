@extends('layouts.app')

@section('title', 'จัดการสินค้า')

@section('content')
<div class="pt-3 pb-2 mb-3">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-4">
        <h1 class="h2 mb-0">รายการสินค้า (Products)</h1>

        <div class="d-flex gap-2">
            <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อ, รหัส SKU..." value="{{ $search }}">
                    <button class="btn btn-outline-secondary" type="submit"><span data-feather="search" style="width: 14px;"></span></button>
                </div>
            </form>
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
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
                            <th class="py-3 text-center" style="width: 70px;">ภาพ</th>
                            <th class="py-3">รหัส / ชื่อสินค้า</th>
                            <th class="py-3">หมวดหมู่</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="py-3">ร้านค้า</th>
                            @endif
                            <th class="py-3 text-end">ต้นทุน</th>
                            <th class="py-3 text-end">ราคาขาย</th>
                            <th class="py-3 text-center">สถานะ</th>
                            <th class="py-3 text-center" style="width: 120px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="text-center">
                                @if($product->image_path)
                                    <img src="{{ asset('images/' . $product->image_path) }}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto text-muted" style="width: 40px; height: 40px;">
                                        <span data-feather="box"></span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $product->name }}</div>
                                <div class="text-muted small">SKU: {{ $product->sku ?? '-' }}</div>
                            </td>
                            <td>
                                @if($product->category)
                                    <span class="badge bg-light text-dark border">{{ $product->category->name }}</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                            @if(auth()->user()->role === 'admin')
                                <td><span class="badge bg-secondary rounded-pill px-2 fw-normal">{{ $product->shop->name ?? '-' }}</span></td>
                            @endif

                            <td class="text-end text-danger">{{ number_format($product->cost, 2) }}</td>
                            <td class="text-end text-success fw-bold">{{ number_format($product->price, 2) }}</td>

                            <td class="text-center">
                                @if($product->is_active)
                                    <span class="badge bg-success rounded-pill px-3 py-1 fw-normal">เปิดขาย</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 py-1 fw-normal">ปิด</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning">
                                        <span data-feather="edit" style="width: 14px; height: 14px;"></span>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบสินค้านี้?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <span data-feather="trash-2" style="width: 14px; height: 14px;"></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 7 }}" class="text-center text-muted py-5">
                                <span data-feather="package" style="width: 48px; height: 48px;" class="mb-2 text-secondary"></span>
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
