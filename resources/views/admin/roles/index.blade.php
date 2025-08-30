@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Role Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Roles</li>
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
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary float-end">
                        <i class="fa fa-plus"></i> Add New Role
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col" class="text-center">Permissions Count</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roles as $role)
                                <tr>
                                    <th scope="row">{{ $role->id }}</th>
                                    <td>{{ $role->name }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-primary">{{ $role->permissions_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>

                                        <form 
                                            action="{{ route('admin.roles.destroy', $role) }}" 
                                            method="POST" 
                                            id="delete-form-{{ $role->id }}" 
                                            style="display:inline;"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="button" 
                                                class="btn btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmationModal"
                                                data-form-id="delete-form-{{ $role->id }}">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No roles found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection