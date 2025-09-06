@extends('layouts.app')

@section('title', 'Main Categories')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Main Categories</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Main Categories</li>
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
                <div class="pb-0 card-header">
                    <a href="{{ route('store.product-main-categories.create') }}" class="btn btn-primary float-end">
                        <i class="fa fa-plus"></i> Add New 
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col" class="text-center">Tenant</th>
                                    <th scope="col">Name</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mainCategories as $item)
                                <tr>
                                    <th scope="row">{{ $item->id }}</th>
                                    <td>{{ $item->tenant->name }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('store.product-main-categories.edit', $item->id) }}" class="btn btn-primary">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>

                                        <form 
                                            action="{{ route('store.product-main-categories.destroy', $item->id) }}" 
                                            method="POST" 
                                            id="delete-form-{{ $item->id }}" 
                                            style="display:inline;"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="button" 
                                                class="btn btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmationModal"
                                                data-form-id="delete-form-{{ $item->id }}">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Items found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $mainCategories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection