@extends('layouts.app')
@section('title', 'Tenant Management')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        {{-- Breadcrumb --}}
        <div class="row">
            <div class="col-sm-6">
                <h3>Tenant Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Tenant</li>
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
                    <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary float-end">
                        <i class="fa fa-plus"></i> Add New Tenant
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tenants as $tenant)
                                <tr>
                                    <th>{{ $tenant->id }}</th>
                                    <td>{{ $tenant->name }}</td>
                                    <td><span
                                            class="badge {{ $tenant->status == 'active' ? 'badge-success' : 'badge-danger' }}">{{
                                            ucfirst($tenant->status) }}</span></td>
                                    <td>{{ $tenant->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.tenants.edit', $tenant->id) }}"
                                            class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                        <form id="delete-form-{{ $tenant->id }}"
                                            action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmationModal"
                                                data-form-id="delete-form-{{ $tenant->id }}">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No tenants found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $tenants->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection