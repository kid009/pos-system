@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Permissions Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Permissions</li>
                </ol>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary float-end">Add New Permissions</a>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="mt-3 table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.permissions.edit', $item->id) }}" class="btn btn-primary">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>

                                        <form action="{{ route('admin.permissions.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this permission?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $permissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection