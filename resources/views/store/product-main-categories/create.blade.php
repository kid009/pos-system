@extends('layouts.app')

@section('title', 'Create New Role')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Main Categories</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('store.product-main-categories.index') }}">Main Categories</a></li>
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
                <div class="card-body">
                    <form method="POST" action="{{ route('store.product-main-categories.store') }}">
                        @csrf
                        @include('store.product-main-categories._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection