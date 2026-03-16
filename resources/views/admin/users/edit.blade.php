@extends('layouts.app')

@section('title', 'แก้ไขผู้ใช้งาน')

@section('content')
<div class="pt-3 pb-2 mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">จัดการผู้ใช้งาน</a></li>
            <li class="breadcrumb-item active" aria-current="page">แก้ไขผู้ใช้งาน</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">แก้ไขผู้ใช้งาน: {{ $user->name }}</h1>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
            <span data-feather="arrow-left"></span> ย้อนกลับ
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('admin.users.partials._form')
                        <div class="mt-4 border-top pt-3 text-end">
                            <button type="submit" class="btn btn-warning px-4">
                                <span data-feather="save"></span> อัปเดตข้อมูล
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
