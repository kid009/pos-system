@extends('layouts.app')

@section('title', 'Create New User')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>User Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Create New User</h5>
                </div>
                <div class="card-body">
                    {{-- ปุ่ม Submit จะยังไม่ทำงาน เราจะมาทำ Logic ใน Day 6 --}}
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        
                        @include('admin.users._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection