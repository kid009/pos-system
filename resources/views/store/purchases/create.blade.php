@extends('layouts.app')
@section('title', 'Stock In / New Purchase')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Purchase Order</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Purchase Order</li>
                </ol>
            </div>
            <div class="col-sm-6">
                
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <livewire:store.purchases.create-purchase>
    {{-- <form method="POST" action="{{ route('store.purchases.store') }}">
        @csrf
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header"><h5>New Purchase Order</h5></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="purchase_date" class="form-label">Purchase Date</label>
                                    <input type="date" class="form-control" name="purchase_date" id="purchase_date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="supplier_name" class="form-label">Supplier Name (Optional)</label>
                                    <input type="text" class="form-control" name="supplier_name" id="supplier_name">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h6>Products</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Cost (per item)</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="purchase_items_body"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total Cost:</strong></td>
                                        <td id="total_cost_display">0.00</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <button type="button" class="btn btn-info" id="add_item_btn">+ Add Item</button>
                        <hr>
                        <button type="submit" class="btn btn-primary">Save Purchase</button>
                    </div>
                </div>
            </div>
        </div>
    </form> --}}
</div>
@endsection

{{-- @push('scripts')
<script>
    $(document).ready(function() {
        let itemIndex = 0;

        $('#add_item_btn').on('click', function() {
            let newRow = `
                <tr class="item-row">
                    <td>
                        <select name="items[${itemIndex}][product_id]" class="form-select product-select" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity-input" min="1" required></td>
                    <td><input type="number" name="items[${itemIndex}][cost]" class="form-control cost-input" step="0.01" min="0" required></td>
                    <td class="subtotal">0.00</td>
                    <td><button type="button" class="btn btn-danger remove-item-btn">X</button></td>
                </tr>
            `;
            $('#purchase_items_body').append(newRow);
            itemIndex++;
        });

        // Remove item
        $('#purchase_items_body').on('click', '.remove-item-btn', function() {
            $(this).closest('.item-row').remove();
            updateTotalCost();
        });

        // Update subtotal and total on input change
        $('#purchase_items_body').on('input', '.quantity-input, .cost-input', function() {
            let row = $(this).closest('.item-row');
            let quantity = parseFloat(row.find('.quantity-input').val()) || 0;
            let cost = parseFloat(row.find('.cost-input').val()) || 0;
            let subtotal = quantity * cost;
            row.find('.subtotal').text(subtotal.toFixed(2));
            updateTotalCost();
        });

        function updateTotalCost() {
            let totalCost = 0;
            $('.item-row').each(function() {
                let subtotal = parseFloat($(this).find('.subtotal').text()) || 0;
                totalCost += subtotal;
            });
            $('#total_cost_display').text(totalCost.toFixed(2));
        }

        // Add one row initially
        $('#add_item_btn').click();
    });
</script>
@endpush --}}