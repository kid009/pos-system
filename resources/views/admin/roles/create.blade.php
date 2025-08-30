@extends('layouts.app')

@section('title', 'Create New Role')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Role Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
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
                    <h5>Create New Role</h5>
                </div>
                <div class="card-body">
                    {{-- เราจะมาเขียน Logic การบันทึก (action, store) ใน Day 4 --}}
                    <form method="POST" action="{{ route('admin.roles.store') }}">
                        @csrf
                        @include('admin.roles._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection