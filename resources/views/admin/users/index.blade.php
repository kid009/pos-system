@extends('layouts.app')

@section('title', 'จัดการผู้ใช้งาน')

@section('content')
<div class="pt-3 pb-2 mb-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-4">
        <h1 class="h2 mb-0">จัดการผู้ใช้งาน (Users)</h1>
        <div class="d-flex gap-2">
            <form action="{{ route('users.index') }}" method="GET" class="d-flex">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อ, อีเมล..." value="{{ $search }}">
                    <button class="btn btn-outline-secondary" type="submit"><span data-feather="search"></span></button>
                </div>
            </form>
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                <span data-feather="plus"></span>
                เพิ่มผู้ใช้งาน
            </a>
        </div>
    </div>

    @include('layouts.partials.alerts')

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 text-center" style="width: 80px;">ลำดับ</th>
                            <th class="py-3">ชื่อ-นามสกุล</th>
                            <th class="py-3">อีเมล</th>
                            <th class="py-3">บทบาท</th>
                            <th class="py-3">ร้านค้า</th>
                            <th class="py-3 text-center">สถานะ</th>
                            <th class="py-3 text-center" style="width: 120px;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td class="px-4 py-3 text-center">{{ $users->firstItem() + $index }}</td>
                            <td class="fw-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger rounded-pill px-3">Administrator</span>
                                @elseif($user->role === 'owner')
                                    <span class="badge bg-primary rounded-pill px-3">Owner</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3">Staff</span>
                                @endif
                            </td>
                            <td>{{ $user->shop->name ?? 'ไม่ระบุ' }}</td>
                            <td class="text-center">
                                @if($user->is_active)
                                    <span class="badge bg-success rounded-pill px-3">เปิดใช้งาน</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3">ปิดใช้งาน</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning" title="แก้ไข">
                                        <span data-feather="edit" style="width: 14px; height: 14px;"></span>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบผู้ใช้งานนี้?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบ">
                                            <span data-feather="trash-2" style="width: 14px; height: 14px;"></span>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <span data-feather="users" style="width: 48px; height: 48px;" class="mb-2 text-secondary"></span>
                                <h5>ไม่พบข้อมูลผู้ใช้งาน</h5>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($users->hasPages())
        <div class="card-footer bg-white py-3 border-0 d-flex justify-content-end">
             {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
