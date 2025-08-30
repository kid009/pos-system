@extends('layouts.app')
@section('title', 'Create New Tenant')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Tenant Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Tenant</a></li>
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
                    <form method="POST" action="{{ route('admin.tenants.store') }}">
                        @csrf
                        @include('admin.tenants._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection