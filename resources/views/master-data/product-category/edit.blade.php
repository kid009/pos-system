@extends('layouts.app')

@section('title', 'แก้ไขหมวดหมู่สินค้า')

@section('content')
<div class="pt-3 pb-2 mb-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-4">
        <h1 class="h2 mb-0">แก้ไขหมวดหมู่สินค้า: <span class="text-primary">{{ $productCategory->name }}</span></h1>
        <a href="{{ route('product-categories.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
            <span data-feather="arrow-left"></span> กลับหน้ารายการ
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('product-categories.update', $productCategory->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @include('master-data.product-category.partials._form', ['productCategory' => $productCategory])

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">อัปเดต: {{ $productCategory->updated_at->format('d/m/Y H:i') }}</small>
                            <div class="d-flex gap-2">
                                <a href="{{ route('product-categories.index') }}" class="btn btn-light border">ยกเลิก</a>
                                <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-1">
                                    <span data-feather="save"></span> อัปเดตข้อมูล
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection