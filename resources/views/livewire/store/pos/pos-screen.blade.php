<div>
    {{-- Single Root Element --}}
    <div class="container-fluid">
        <div class="row">
            {{-- Left Side: Product List --}}
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search products by name..." wire:model.live.debounce.300ms="search">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse ($products as $product)
                            <div class="mb-3 col-md-3">
                                <div class="card h-100" wire:click="addToCart({{ $product->id }})" style="cursor: pointer;">
                                    <img src="{{ $product->image ? Storage::url($product->image) : asset('assets/images/dashboard/1.png') }}" class="card-img-top" style="height: 100px; object-fit: cover;" alt="{{ $product->name }}">
                                    <div class="p-2 text-center card-body">
                                        <h6 class="card-title" style="font-size: 14px;">{{ $product->name }}</h6>
                                        <p class="card-text fw-bold">{{ number_format($product->price, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center col-12">
                                <p>No products found.</p>
                            </div>
                            @endforelse
                        </div>
                        <div class="mt-3">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side: Cart --}}
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5>Current Order</h5>
                    </div>
                    <div class="card-body">
                        {{-- ... เนื้อหาของ Cart ทั้งหมดเหมือนเดิม ... --}}
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select wire:model.live="selectedCustomerId" id="customer_id" class="form-select">
                                <option value="">Walk-in Customer</option>
                                @foreach($allCustomers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="table-responsive" style="min-height: 200px;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cart as $productId => $item)
                                    <tr>
                                        <td>
                                            {{ $item['name'] }}
                                            <a href="#" wire:click="removeItem({{ $productId }})" class="text-danger ms-2"><i class="fa fa-trash"></i></a>
                                        </td>
                                        <td>
                                            <div class="input-group" style="width: 120px;">
                                                <button class="btn btn-sm btn-secondary" wire:click="decrementQuantity({{ $productId }})">-</button>
                                                <input type="text" class="text-center form-control form-control-sm" value="{{ $item['quantity'] }}" readonly>
                                                <button class="btn btn-sm btn-secondary" wire:click="incrementQuantity({{ $productId }})">+</button>
                                            </div>
                                        </td>
                                        <td class="text-end">{{ number_format($item['price'], 2) }}</td>
                                        <td class="text-end">{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Cart is empty.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5>Subtotal</h5>
                            <h5>{{ number_format($cartSubtotal, 2) }}</h5>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h5>Discount</h5>
                            <h5>0.00</h5>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h4>Total</h4>
                            <h4>{{ number_format($cartSubtotal, 2) }}</h4>
                        </div>
                        <div class="gap-2 mt-3 d-grid">
                            <button class="btn btn-primary btn-lg" type="button"
                                    wire:click="checkout"
                                    wire:loading.attr="disabled"
                                    {{ empty($cart) || empty($selectedCustomerId) ? 'disabled' : '' }}>

                                <span wire:loading.remove wire:target="checkout">
                                    Checkout
                                </span>
                                <span wire:loading wire:target="checkout">
                                    Processing...
                                </span>
                            </button>
                            {{-- แสดง validation error สำหรับ Customer --}}
                            @error('selectedCustomerId') <span class="mt-2 text-danger d-block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>