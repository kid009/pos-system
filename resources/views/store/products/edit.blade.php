@extends('layouts.app')

@section('title', 'Create New Product')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Products</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('store.products.index') }}">Products</a></li>
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
                    <form method="POST" action="{{ route('store.products.update', $product) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('store.products._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection