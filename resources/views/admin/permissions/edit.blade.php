@extends('layouts.app')

@section('title', 'Edit Permission')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Permission Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                    <li class="breadcrumb-item active">Update</li>
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
                    <h5>Update New Permission</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.permissions.update', $permission) }}">
                        @method('PUT')
                        @include('admin.permissions._form', ['permission' => $permission])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection