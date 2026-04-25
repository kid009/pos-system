@extends('layouts.app')

@section('title', 'เพิ่มช่องทางการขายใหม่')

@section('content')
<div class="pt-3 pb-2 mb-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-4">
        <h1 class="h2 mb-0">เพิ่มช่องทางการขายใหม่</h1>
        <a href="{{ route('sales-channels.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
            <span data-feather="arrow-left"></span> กลับหน้ารายการ
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('sales-channels.store') }}" method="POST">
                        @csrf

                        @include('master-data.sales-channel.partials._form')

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('sales-channels.index') }}" class="btn btn-light border">ยกเลิก</a>
                            <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-1">
                                <span data-feather="save"></span> บันทึกข้อมูล
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection