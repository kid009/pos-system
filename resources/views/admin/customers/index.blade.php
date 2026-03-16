@extends('layouts.app')
@section('title', 'ฐานข้อมูลลูกค้า')

@section('content')
<div class="pt-3 pb-2 mb-3 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom">
    <h1 class="h2">รายชื่อลูกค้า</h1>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <span data-feather="plus"></span> เพิ่มลูกค้าใหม่
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('customers.index') }}" method="GET" class="d-flex gap-2 w-50">
            <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อ, เบอร์โทร..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-dark">ค้นหา</button>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">ล้าง</a>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>รหัส</th>
                    <th>ชื่อลูกค้า</th>
                    <th>เบอร์โทร</th>
                    @if(auth()->user()->role === 'admin') <th>สาขา</th> @endif
                    <th>แต้มสะสม</th>
                    <th>สถานะ</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td>CUS-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="fw-bold">{{ $customer->name }}</td>
                    <td>{{ $customer->phone ?? '-' }}</td>
                    @if(auth()->user()->role === 'admin') <td>{{ $customer->shop->name ?? '-' }}</td> @endif
                    <td class="text-success fw-bold">{{ number_format($customer->points) }}</td>
                    <td>
                        @if($customer->is_active)
                            <span class="badge bg-success">ปกติ</span>
                        @else
                            <span class="badge bg-danger">ระงับ</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-primary">แก้ไข</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">ไม่พบข้อมูลลูกค้า</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-0">{{ $customers->links() }}</div>
</div>
@endsection
