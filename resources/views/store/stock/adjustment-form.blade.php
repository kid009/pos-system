@extends('layouts.app')
@section('title', 'Stock Adjustment')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>stock adjustment</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">stock adjustment</li>
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
                <div class="card-body">
                    <form method="POST" action="{{ route('store.stock.adjustment.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select name="product_id" id="product_id"
                                class="form-select @error('product_id') is-invalid @enderror" >
                                <option value="">-- Select Product --</option>
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id')==$product->id ? 'selected' : ''}}>
                                    {{ $product->name }} (SKU: {{ $product->sku ?? 'N/A' }})
                                </option>
                                @endforeach
                            </select>
                            @error('product_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Adjustment Type</label>
                                    <select name="type" id="type"
                                        class="form-select @error('type') is-invalid @enderror" >
                                        <option value="">-- Select Type --</option>
                                        <option value="add" {{ old('type')=='add' ? 'selected' : '' }}>ADD Stock (e.g., Found items)</option>
                                        <option value="remove" {{ old('type')=='remove' ? 'selected' : '' }}>REMOVE Stock (e.g., Damaged, Lost)</option>
                                    </select>
                                    @error('type')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                        name="quantity" id="quantity" value="{{ old('quantity') }}"  min="1">
                                    @error('quantity')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes / Reason</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"
                                rows="3" >{{ old('notes') }}</textarea>
                            @error('notes')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Adjustment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection