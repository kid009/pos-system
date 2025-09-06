@extends('layouts.app')
@section('title', 'Customer Management')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Customers Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Customers</li>
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
                    <a href="{{ route('store.customers.create') }}" class="btn btn-primary float-end">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customers as $customer)
                                    <tr>
                                        <th>{{ $customer->id }}</th>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->phone ?? '-' }}</td>
                                        <td>{{ Str::limit($customer->address, 50) }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('store.customers.edit', $customer) }}" class="btn btn-primary">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            
                                            <form id="delete-form-{{ $customer->id }}" action="{{ route('store.customers.destroy', $customer) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-form-id="delete-form-{{ $customer->id }}">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">No customers found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $customers->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection