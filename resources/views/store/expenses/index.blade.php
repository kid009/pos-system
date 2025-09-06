@extends('layouts.app')
@section('title', 'Expense Management')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Expense</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Expense</li>
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
                    <a href="{{ route('store.expenses.create') }}" class="btn btn-primary float-end">
                        <i class="fa fa-plus"></i> Add New 
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Expense Date</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($expenses as $expense)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
                                        <td>{{ $expense->expenseCategory->name ?? 'N/A' }}</td>
                                        <td>{{ $expense->description }}</td>
                                        <td class="text-end">{{ number_format($expense->amount, 2) }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('store.expenses.edit', $expense->id) }}" class="btn btn-primary">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>

                                            <form id="delete-form-{{ $expense->id }}" action="{{ route('store.expenses.destroy', $expense->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-form-id="delete-form-{{ $expense->id }}">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                            
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">No expenses found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $expenses->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection