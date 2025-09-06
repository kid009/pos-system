@extends('layouts.app')

@section('title', 'Create New Categories')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Categories</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('store.product-categories.index') }}">Categories</a></li>
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
                    <form method="POST" action="{{ route('store.product-categories.store') }}">
                        @csrf
                        @include('store.product-categories._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection