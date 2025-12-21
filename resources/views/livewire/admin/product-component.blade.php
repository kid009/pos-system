<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Products</h1>
        <button wire:click="create" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Product
        </button>
    </div>

    <div class="mb-3">
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search product name">
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock_qty }}</td>
                            <td>
                                <button wire:click="edit({{ $product->id }})" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger"
                                        @click="$dispatch('open-confirm-modal', {
                                            component: '{{ $this->getId() }}',
                                            method: 'delete',
                                            params: {{ $product->id }},
                                            title: 'Delete Product?',
                                            message: 'Do you want delete item?'
                                        })">
                                        <i class="fas fa-trash"></i>
                                    </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $products->links() }}
        </div>
    </div>

    @if($isOpen)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5); overflow-y: auto;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $productId ? 'Edit Product' : 'Create Product' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="{{ $productId ? 'update' : 'store' }}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select wire:model="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Cost</label>
                                <input type="number" step="0.01" wire:model="cost" class="form-control @error('cost') is-invalid @enderror">
                                @error('cost') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" wire:model="price" class="form-control @error('price') is-invalid @enderror">
                                @error('price') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock Qty</label>
                                <input type="number" wire:model="stock_qty" class="form-control @error('stock_qty') is-invalid @enderror">
                                @error('stock_qty') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" wire:click="closeModal" class="btn btn-secondary">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
