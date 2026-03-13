@extends('layouts.app')

@section('title', 'หน้าหลัก - POS System')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">ภาพรวมระบบ (Dashboard)</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">แชร์</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">ส่งออก (Export)</button>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar"></span>
                สัปดาห์นี้
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">ยอดขายวันนี้</h5>
                    <p class="card-text fs-2">฿0.00</p>
                </div>
            </div>
        </div>
    </div>
@endsection
