@extends('layouts.app')
@section('title', 'Edit Tenant')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Tenant Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.tenants.index') }}">Tenant</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.tenants.update', $tenant) }}">
                        @csrf
                        @method('PUT')
                        @include('admin.tenants._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection