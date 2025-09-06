<div>
    <form wire:submit="save">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>New Purchase Order</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="purchase_date" class="form-label">Purchase Date</label>
                                    <input type="date" class="form-control @error('purchase_date') is-invalid @enderror"
                                        wire:model="purchase_date" id="purchase_date">
                                    @error('purchase_date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="supplier_name" class="form-label">Supplier Name (Optional)</label>
                                    <input type="text" class="form-control" wire:model="supplier_name"
                                        id="supplier_name">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h6>Products</h6>
                        @error('items') <span class="mb-2 text-danger d-block">{{ $message }}</span> @enderror
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
                                <tbody>
                                    @foreach($items as $index => $item)
                                    <tr>
                                        <td>
                                            <select wire:model="items.{{ $index }}.product_id" class="form-select"
                                                required>
                                                <option value="">Select Product</option>
                                                @foreach($allProducts as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" wire:model.live="items.{{ $index }}.quantity"
                                                class="form-control" min="1" required>
                                        </td>
                                        <td>
                                            <input type="number" wire:model.live="items.{{ $index }}.cost"
                                                class="form-control" step="0.01" min="0" required>
                                        </td>
                                        <td>
                                            {{ number_format(($items[$index]['quantity'] ?? 0) * ($items[$index]['cost']
                                            ?? 0), 2) }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger"
                                                wire:click="removeItem({{ $index }})">X</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="mt-2 btn btn-info" wire:click="addItem">+ Add Item</button>
                        <hr>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('store.purchases.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>