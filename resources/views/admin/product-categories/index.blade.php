@extends('layouts.admin')

@section('title', 'หมวดหมู่สินค้า')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="index.html">จัดการสินค้า</a></li>
<li class="breadcrumb-item">หมวดหมู่สินค้า</li>
<li class="breadcrumb-item active">หน้าแรก</li>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">

        {{-- แสดงข้อความ Success/Error --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="card">
            <div class="pb-0 card-header">
                <div class="d-flex justify-content-between">
                    <h5>รายการหมวดหมู่สินค้า</h5>
                    <a href="{{ route('admin.product-categories.create') }}" class="btn btn-primary">
                        <i data-feather="plus-circle"></i> เพิ่มหมวดหมู่ใหม่
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ชื่อหมวดหมู่</th>
                                <th scope="col">คำอธิบาย</th>
                                <th scope="col" class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- วนลูปแสดงข้อมูล (จะเพิ่มในขั้นตอนถัดไป) --}}
                            @forelse ($categories as $category)
                            <tr>
                                <th scope="row">{{ $category->id }}</th>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->description ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.product-categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
                                        <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.product-categories.destroy', $category->id) }}" method="POST" 
                                          style="display: inline-block;"
                                          onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบหมวดหมู่นี้?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">ยังไม่มีข้อมูลหมวดหมู่</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $categories->links() }}
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection