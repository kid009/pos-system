@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>User Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </div>
            <div class="col-sm-6">
                {{-- เพิ่มปุ่มนี้ --}}
                
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="pb-0 card-header">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary float-end"><i class="fa fa-plus"></i> Add New User</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Tenant</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <th scope="row">{{ $user->id }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $user->getRoleNames()->first() }}</span>
                                    </td>
                                    <td>{{ $user->tenant->name ?? 'N/A' }}</td>
                                    <td>
                                        {{-- Action buttons will go here --}}
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a>

                                        {{-- delete --}}
                                        <form 
                                            action="{{ route('admin.users.destroy', $user->id) }}" 
                                            method="POST" 
                                            id="delete-form-{{ $user->id }}" 
                                            style="display:inline;"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="button" 
                                                class="btn btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmationModal"
                                                data-form-id="delete-form-{{ $user->id }}">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection