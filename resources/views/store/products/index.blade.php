@extends('layouts.app')

@section('title', 'Product Management')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Product Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Product</li>
                </ol>
            </div>
            <div class="col-sm-6">
                
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('store.products.create') }}" class="btn btn-primary float-end">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>SKU</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Cost</th>
                                    <th>Price</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                <tr>
                                    <td class="text-center"> 
                                        @if($product->image)
                                            <img src="{{ asset("uploads/$product->image") }}" alt="{{ $product->name }}" width="60" class="img-thumbnail">
                                        @else
                                            <span class="badge badge-light-danger">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->sku }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->productCategory->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($product->cost, 2) }}</td>
                                    <td>{{ number_format($product->price, 2) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('store.products.edit', $product->id) }}" class="btn btn-primary">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>

                                        <form id="delete-form-{{ $product->id }}" action="{{ route('store.products.destroy', $product) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteConfirmationModal"
                                                    data-form-id="delete-form-{{ $product->id }}">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No products found. Please create one.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $products->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection