@extends('layouts.app')
@section('title', 'Purchase History')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Purchase History</h3>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('store.purchases.create') }}" class="btn btn-primary float-end">
                    <i class="fa fa-plus"></i> New Stock In / Purchase
                </a>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header"><h5>All Purchase Orders</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>PO #</th>
                                    <th>Purchase Date</th>
                                    <th>Supplier</th>
                                    <th>Total Cost</th>
                                    <th>Created By</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchases as $purchase)
                                    <tr>
                                        <th>{{ $purchase->id }}</th>
                                        <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}</td>
                                        <td>{{ $purchase->supplier_name ?? '-' }}</td>
                                        <td>{{ number_format($purchase->total_cost, 2) }}</td>
                                        <td>{{ $purchase->creator->name ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('store.purchases.show', $purchase->id) }}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center">No purchase history found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $purchases->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection