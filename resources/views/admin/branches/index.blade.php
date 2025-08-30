@extends('layouts.app')
@section('title', 'Branch Management')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Branches Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Branches</li>
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
                    <a href="{{ route('admin.branches.create') }}" class="btn btn-primary float-end">
                        <i class="fa fa-plus"></i>Add New Branches
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Branch Name</th>
                                    <th>Tenant</th>
                                    <th>Phone</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($branches as $branch)
                                    <tr>
                                        <th>{{ $branch->id }}</th>
                                        <td>{{ $branch->name }}</td>
                                        <td>
                                            <a href="{{ route('admin.tenants.edit', $branch->tenant) }}">{{ $branch->tenant->name ?? 'N/A' }}</a>
                                        </td>
                                        <td>{{ $branch->phone ?? '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                            <form id="delete-form-{{ $branch->id }}" action="{{ route('admin.branches.destroy', $branch) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-form-id="delete-form-{{ $branch->id }}">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">No branches found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $branches->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection