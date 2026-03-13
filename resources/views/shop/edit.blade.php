@extends('layouts.app')

@section('title', 'แก้ไขร้านค้า: ' . $shop->name)

@section('content')
    <div class="pt-3 pb-2 mb-3">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-4">
            <h1 class="h2 mb-0">แก้ไขร้านค้า: <span class="text-primary">{{ $shop->name }}</span></h1>
            <a href="{{ route('shop.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                <span data-feather="arrow-left"></span> กลับหน้ารายการ
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8 col-xl-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">

                        <form action="{{ route('shop.update', $shop->id) }}" method="POST">
                            @csrf
                            @method('PUT') @include('shop.partials._form', ['shop' => $shop])

                            <hr class="my-4">

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">อัปเดตล่าสุด: {{ $shop->updated_at->format('d/m/Y H:i') }}</small>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('shop.index') }}" class="btn btn-light border">ยกเลิก</a>
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
