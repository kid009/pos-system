<div class="row g-0" x-data="posSystem()">

    <div class="col-md-8 product-panel p-3 bg-light" style="height: 100vh; overflow-y: auto; padding-bottom: 100px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold m-0 text-dark"><i class="fas fa-store me-2"></i>Product Catalog</h4>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
            @endif

            <button type="button" wire:click="logout"
                wire:confirm="Are you sure you want to Logout? (คุณต้องการออกจากระบบใช่ไหม?)"
                class="btn btn-danger btn-sm fw-bold">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </button>
        </div>

        <div class="mb-4">
            <h6 class="text-muted mb-2 small fw-bold text-uppercase">Categories</h6>
            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <button type="button"
                        class="btn w-100 py-3 shadow-sm {{ $category_id === null ? 'btn-primary' : 'btn-white bg-white border' }}"
                        wire:click="$set('category_id', null)">
                        <i class="fas fa-th-large me-1"></i> All Items
                    </button>
                </div>
                @foreach ($categories as $cat)
                    <div class="col-6 col-md-3">
                        <button type="button"
                            class="btn w-100 py-3 shadow-sm text-truncate {{ $category_id == $cat->id ? 'btn-primary' : 'btn-white bg-white border' }}"
                            wire:click="$set('category_id', {{ $cat->id }})">
                            {{ $cat->name }}
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <h6 class="text-muted mb-2 small fw-bold text-uppercase">Products</h6>
        <div class="row g-3">
            @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm user-select-none"
                        style="cursor: pointer; transition: transform 0.2s;"
                        onmouseover="this.style.transform='translateY(-5px)'"
                        onmouseout="this.style.transform='translateY(0)'"
                        @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})">

                        <div class="card-body text-center p-3 d-flex flex-column">
                            <div class="bg-white rounded mb-2 d-flex align-items-center justify-content-center border"
                                style="height: 100px;">
                                @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" class="img-fluid"
                                        style="max-height: 80px;">
                                @else
                                    <i class="fas fa-box fa-3x text-secondary opacity-25"></i>
                                @endif
                            </div>
                            <div class="mt-auto">
                                <h6 class="card-title text-dark small fw-bold mb-1 text-truncate">{{ $product->name }}
                                </h6>
                                <div class="text-primary fw-bold">{{ number_format($product->price, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="fas fa-box-open fa-3x mb-3 opacity-50"></i>
                    <p>No products found.</p>
                </div>
            @endforelse
        </div>
    </div>


    <div class="col-md-4 cart-panel shadow bg-white" style="height: 100vh; display: flex; flex-direction: column;">
        <div class="p-3 bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="m-0 fw-bold"><i class="fas fa-shopping-cart me-2"></i> Current Order</h5>
            <span class="badge bg-white text-primary rounded-pill px-3" x-text="cart.length + ' Items'"></span>
        </div>

        <div class="cart-items p-0" style="flex-grow: 1; overflow-y: auto;">
            <table class="table table-striped table-hover mb-0">
                <thead class="bg-light sticky-top" style="z-index: 1;">
                    <tr>
                        <th class="ps-3">Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end pe-3">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in cart" :key="item.id">
                        <tr>
                            <td class="align-middle ps-3">
                                <div class="fw-bold text-truncate" style="max-width: 140px;" x-text="item.name"></div>
                                <div class="small text-muted" x-text="formatNumber(item.price)"></div>
                            </td>
                            <td class="align-middle text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-secondary" @click="updateQty(index, -1)">-</button>
                                    <button class="btn btn-white border disabled text-dark fw-bold" style="width: 35px;"
                                        x-text="item.qty"></button>
                                    <button class="btn btn-outline-secondary" @click="updateQty(index, 1)">+</button>
                                </div>
                            </td>
                            <td class="align-middle text-end fw-bold pe-3" x-text="formatNumber(item.price * item.qty)">
                            </td>
                            <td class="align-middle text-center">
                                <button class="btn btn-link text-danger p-0" @click="removeItem(index)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="cart.length === 0">
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-shopping-basket fa-2x mb-3 opacity-50"></i><br>Select products to start
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="cart-summary bg-light p-3 border-top">
            <div class="d-flex justify-content-between mb-2 small text-muted">
                <span>Subtotal</span><span class="fw-bold text-dark" x-text="formatNumber(grandTotal)"></span>
            </div>
            <div class="d-flex justify-content-between mb-3 small text-muted">
                <span>Tax (0%)</span><span class="fw-bold text-dark">0.00</span>
            </div>
            <div class="d-flex justify-content-between mb-4 pb-3 border-bottom">
                <h4 class="fw-bold text-dark">Total</h4>
                <h4 class="fw-bold text-primary" x-text="formatNumber(grandTotal)"></h4>
            </div>
            <button
                class="btn btn-success w-100 py-3 fw-bold fs-5 shadow-sm text-uppercase d-flex justify-content-between px-4"
                :disabled="cart.length === 0" @click="$dispatch('open-checkout-modal', { total: grandTotal })">
                <span>Checkout</span><span x-text="formatNumber(grandTotal)"></span>
            </button>
        </div>
    </div>

    <div x-data="{
        showModal: false,
        receivedAmount: '',
        change: 0,
        currentTotal: 0,
        paymentMethod: 'cash',
        customerId: '', // ✅ 1. เพิ่มตัวแปรเก็บ ID ลูกค้า

        openCheckout(detail) {
            this.showModal = true;
            this.receivedAmount = '';
            this.change = 0;
            this.paymentMethod = 'cash';
            this.customerId = ''; // Reset ลูกค้าเป็นทั่วไปทุกครั้ง
            this.currentTotal = detail.total;

            setTimeout(() => {
                let input = document.getElementById('receivedInput');
                if (input) input.focus();
            }, 100);
        },

        calculateChange() {
            let received = parseFloat(this.receivedAmount) || 0;
            this.change = received - this.currentTotal;
        },

        confirmPayment() {
            let received = parseFloat(this.receivedAmount) || 0;
            let total = this.currentTotal;

            if (this.paymentMethod !== 'unpaid' && received < total) {
                alert('Amount received is not enough!');
                return;
            }

            // ✅ 3. ส่ง customerId (หรือ null) ไป Backend ตัวสุดท้าย
            $wire.checkout(this.cart, total, received, this.paymentMethod, this.customerId || null)
                .then(() => {
                    this.showModal = false;
                })
                .catch(err => {
                    console.error(err);
                    this.showModal = false;
                    alert('Something went wrong. Please try again.');
                });
        }
    }" @open-checkout-modal.window="openCheckout($event.detail)" class="position-relative"
        style="z-index: 2000;">

        <div x-show="showModal" class="modal fade" :class="{ 'show d-block': showModal }"
            style="background: rgba(0,0,0,0.6); display: none;" x-transition.opacity>

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-money-bill-wave me-2"></i> Payment</h5>
                        <button type="button" class="btn-close btn-close-white" @click="showModal = false"></button>
                    </div>

                    <div class="modal-body text-center p-4">
                        <h3 class="text-muted mb-2">Total Amount</h3>
                        <h1 class="fw-bold text-success display-4 mb-4" x-text="formatNumber(currentTotal)"></h1>

                        <div class="form-group mb-3 text-start">
                            <label class="fw-bold mb-1"><i class="fas fa-user me-1"></i> Customer (ลูกค้า)</label>
                            <select x-model="customerId" class="form-select form-select-lg">
                                <option value="">👤 General Customer (ลูกค้าทั่วไป)</option>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}">
                                        {{ $c->name }} {{ $c->phone ? '(' . $c->phone . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3 text-start">
                            <label class="fw-bold mb-1">Payment Method</label>
                            <select x-model="paymentMethod" class="form-select form-select-lg fw-bold">
                                <option value="cash">💵 เงินสด (Cash)</option>
                                <option value="transfer">🏦 โอนธนาคาร (Bank Transfer)</option>
                                <option value="half_half">🏛️ โครงการคนละครึ่ง</option>
                                <option value="unpaid">📝 ค้างชำระ (Unpaid/Credit)</option>
                            </select>
                        </div>

                        <div class="form-group mb-4 text-start">
                            <label class="fw-bold mb-1">Received Amount</label>
                            <input type="number" id="receivedInput"
                                class="form-control form-control-lg text-center fw-bold fs-3" x-model="receivedAmount"
                                @input="calculateChange()" @keydown.enter="confirmPayment()" placeholder="0.00">
                        </div>

                        <div class="alert alert-light border d-flex justify-content-between px-4 py-3"
                            x-show="paymentMethod === 'cash'">
                            <span class="fs-5 text-muted">Change:</span>
                            <span class="fs-4 fw-bold" :class="change < 0 ? 'text-danger' : 'text-success'"
                                x-text="formatNumber(change)"></span>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-center p-3">
                        <button class="btn btn-secondary btn-lg px-4" @click="showModal = false">Cancel</button>
                        <button class="btn btn-success btn-lg px-5 fw-bold" @click="confirmPayment()"
                            :disabled="paymentMethod !== 'unpaid' && (!receivedAmount || change < 0)">
                            CONFIRM PAYMENT
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="printable-area" class="d-none d-print-block bg-white text-dark">
        @if ($lastTransaction)
            <div class="p-3" style="width: 100mm; min-height: 140mm; font-family: 'Sarabun', sans-serif;">

                <div class="text-center mb-3">
                    <p class="fw-bold">บิลเงินสด</p>
                    <p class="fw-bold mb-0">ร้านพีแก๊ส</p>
                    <p class="small mb-0">609 ม.2 ต.ขามทะเลสอ อ.ขามทะเลสอ นครราชสีมา 30280</p>
                </div>

                <div class="row small mb-2">
                    <div class="col-12">
                        <strong>เลขที่:</strong> {{ $lastTransaction->reference_no }}<br>
                        <strong>วันที่:</strong> {{ $lastTransaction->created_at->format('d/m/Y H:i') }}<br>
                        <strong>พนักงานขาย:</strong> {{ $lastTransaction->user->name ?? '-' }}<br>
                        <strong>ลูกค้า:</strong> {{ $lastTransaction->customer->name ?? 'General' }}
                    </div>
                </div>

                <table class="table table-sm border-white small mb-2">
                    <thead>
                        <tr class="border-bottom border-dark">
                            <th>สินค้า</th>
                            <th class="text-center">จำนวน</th>
                            <th class="text-end">ราคา</th>
                            <th class="text-end">ยอดเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lastTransaction->details as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->price, 2) }}</td>
                                <td class="text-end">{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr class="border-dark opacity-100 my-2">

                <div class="d-flex justify-content-between fw-bold">
                    <span>รวมทั้งหมด</span>
                    <span class="fs-5">{{ number_format($lastTransaction->total_amount, 2) }}</span>
                </div>

            </div>
        @endif
    </div>

    <style>
        @media print {

            /* 1. ตั้งค่าหน้ากระดาษเป็น A6 */
            @page {
                size: A6;
                /* หรือกำหนดเป็น mm: size: 105mm 148mm; */
                margin: 0mm;
            }

            /* 2. ซ่อนทุกอย่างในหน้าเว็บ */
            body * {
                visibility: hidden;
                margin: 0;
                padding: 0;
            }

            /* 3. แสดงเฉพาะใบเสร็จ และจัดตำแหน่ง */
            #printable-area,
            #printable-area * {
                visibility: visible;
            }

            #printable-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 5mm;
            }

            /* ซ่อน Header/Footer ของ Browser (บาง Browser อาจต้องตั้งค่าเอง) */
            header,
            footer {
                display: none !important;
            }
        }
    </style>

    <script>
        function formatNumber(num) {
            if (num === undefined || num === null) return '0.00';
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(num);
        }

        function posSystem() {
            return {
                cart: JSON.parse(localStorage.getItem('pos_cart')) || [],
                init() {
                    this.$watch('cart', (value) => {
                        localStorage.setItem('pos_cart', JSON.stringify(value));
                    });
                    Livewire.on('transaction-completed', () => {
                        this.clearCart();
                    });
                },
                addToCart(id, name, price) {
                    let existingItem = this.cart.find(item => item.id === id);
                    if (existingItem) {
                        existingItem.qty++;
                    } else {
                        this.cart.push({
                            id,
                            name,
                            price: parseFloat(price),
                            qty: 1
                        });
                    }
                    this.playBeep();
                },
                updateQty(index, amount) {
                    if (this.cart[index].qty + amount <= 0) {
                        this.removeItem(index);
                    } else {
                        this.cart[index].qty += amount;
                    }
                },
                removeItem(index) {
                    this.cart.splice(index, 1);
                },
                get grandTotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.qty), 0);
                },
                clearCart() {
                    this.cart = [];
                },
                playBeep() {
                    /* Beep code */
                }
            }
        }

        document.addEventListener('livewire:initialized', () => {

            // console.log('Livewire Loaded!'); // ✅ 1. ดูว่าบรรทัดนี้ขึ้นใน Console ไหม

            Livewire.on('print-receipt', () => {
                console.log('Received Print Event!'); // ✅ 2. ถ้ากดจ่ายเงิน บรรทัดนี้ต้องขึ้น

                setTimeout(() => {
                    window.print();
                }, 1000); // เพิ่มเวลาเป็น 1 วินาที เผื่อเครื่องช้า
            });

        });
    </script>
</div>
