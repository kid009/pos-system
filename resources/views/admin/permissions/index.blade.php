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
                {{-- <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary float-end">Add New Permissions</a> --}}
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">

                    <form class='form theme-form' action="{{ route('admin.permissions.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col">
                                <label class="form-label">Add New Permissions</label>
                                <input class='form-control' type="text" name="permission_name">
                                <button type="submit" class="btn btn-primary float-end mt-3">Save</button>
                            </div>
                        </div>
                    </form>


                    <div class="table-responsive mt-3">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->name }}</td>
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