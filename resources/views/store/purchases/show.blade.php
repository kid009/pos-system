@extends('layouts.app')
@section('title', 'Purchase Order #' . $purchase->id)
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Purchase Order #{{ $purchase->id }}</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('store.purchases.index') }}">Purchases</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5>Purchase Details</h5>
                        <a href="{{ route('store.purchases.index') }}" class="btn btn-primary">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4 row">
                        <div class="col-md-6">
                            <p><strong>Purchase Date:</strong> {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('F d, Y') }}</p>
                            <p><strong>Supplier:</strong> {{ $purchase->supplier_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>Recorded By:</strong> {{ $purchase->creator->name ?? 'N/A' }}</p>
                            <p><strong>Recorded At:</strong> {{ $purchase->created_at->format('F d, Y H:i A') }}</p>
                        </div>
                    </div>

                    <h6>Items Included:</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th class="text-end">Quantity</th>
                                    <th class="text-end">Cost (per item)</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchase->items as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'Product not found' }}</td>
                                    <td>{{ $item->product->sku ?? '-' }}</td>
                                    <td class="text-end">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->cost, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->quantity * $item->cost, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total Cost</strong></td>
                                    <td class="text-end"><strong>{{ number_format($purchase->total_cost, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection