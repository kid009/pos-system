<div class="h-100 w-100" style="position: fixed; top: 0; left: 0; overflow: hidden;">

    <!-- ✅ ส่งข้อมูลลูกค้า ($customers) เข้าไปใน Alpine ผ่าน Constructor -->
    <div class="row g-0 h-100" x-data="posSystem(@js($customers))">

        <!-- ================= LEFT: PRODUCT PANEL ================= -->
        <div class="col-md-7 col-lg-8 d-flex flex-column h-100 bg-light border-end">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center p-3 bg-white shadow-sm border-bottom flex-shrink-0">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="fas fa-store fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold m-0 text-dark lh-1">POS Terminal</h5>
                        <small class="text-muted" style="font-size: 0.8rem;">User: {{ auth()->user()->name }}</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    @if (auth()->user()->role != 'staff')
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                            <i class="fas fa-chart-line me-2"></i> <span class="d-none d-sm-inline">Dashboard</span>
                        </a>
                    @endif
                    <button type="button" wire:click="logout" wire:confirm="Confirm Logout?" class="btn btn-danger btn-sm d-flex align-items-center">
                        <i class="fas fa-power-off me-2"></i> <span class="d-none d-sm-inline">ออกจากระบบ</span>
                    </button>
                </div>
            </div>

            <!-- Products -->
            <div class="flex-grow-1 overflow-auto p-3" style="min-height: 0;">
                <div class="mb-4 sticky-top pt-1" style="top: -1rem; z-index: 10; background: transparent;">
                    <div class="d-flex gap-2 overflow-auto pb-2 px-1" style="white-space: nowrap; scrollbar-width: none;">
                        <button type="button" class="btn rounded-pill px-4 shadow-sm border {{ $category_id === null ? 'btn-dark' : 'btn-white bg-white' }}" wire:click="$set('category_id', null)">
                            <i class="fas fa-th-large me-1"></i> ทั้งหมด
                        </button>
                        @foreach ($categories as $cat)
                            <button type="button" class="btn rounded-pill px-4 shadow-sm border {{ $category_id == $cat->id ? 'btn-dark' : 'btn-white bg-white' }}" wire:click="$set('category_id', {{ $cat->id }})">
                                {{ $cat->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="row g-3 pb-5">
                    @forelse($products as $product)
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="card h-100 border-0 shadow-sm user-select-none position-relative overflow-hidden group-hover-effect"
                                style="cursor: pointer; transition: all 0.2s;"
                                @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->category->name ?? '' }}')">
                                <div class="card-body text-center p-2 d-flex flex-column">
                                    <div class="bg-white rounded mb-2 d-flex align-items-center justify-content-center border" style="height: 100px; width: 100%;">
                                        @if ($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" class="img-fluid p-2" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                        @else
                                            <i class="fas fa-box fa-2x text-secondary opacity-25"></i>
                                        @endif
                                    </div>
                                    <div class="mt-auto text-start">
                                        <h6 class="card-title text-dark fw-bold mb-1 lh-sm text-truncate-2" style="font-size: 0.9rem; height: 2.2em;">
                                            {{ $product->name }}
                                        </h6>
                                        <div class="d-flex justify-content-between align-items-end mt-1">
                                            <span class="badge bg-light text-dark border px-1" style="font-size: 0.7rem;">{{ $product->category->name ?? 'General' }}</span>
                                            <span class="text-primary fw-bolder fs-5">{{ number_format($product->price, 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary bg-opacity-10 d-none overlay-hover"></div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted mt-5">
                            <div class="mb-3"><i class="fas fa-search fa-3x opacity-25"></i></div>
                            <h5>ไม่พบข้อมูล</h5>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ================= RIGHT: CART PANEL ================= -->
        <div class="col-md-5 col-lg-4 d-flex flex-column h-100 bg-white shadow-lg border-start position-relative" style="z-index: 100;">
            <!-- Cart Header -->
            <div class="p-3 bg-primary text-white d-flex justify-content-between align-items-center shadow-sm flex-shrink-0">
                <div class="d-flex align-items-center">
                    <i class="fas fa-shopping-cart fa-lg me-2"></i>
                    <h5 class="m-0 fw-bold">รายการสินค้า</h5>
                </div>
                <span class="badge bg-white text-primary rounded-pill px-3 py-1 fw-bold fs-6" x-text="cart.length + ' ชิ้น'"></span>
            </div>

            <!-- Cart Items -->
            <div class="flex-grow-1 overflow-auto bg-light p-2" style="min-height: 0;">
                <div class="d-flex flex-column gap-2">
                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="card border-0 shadow-sm p-2">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="fw-bold text-dark" x-text="item.name"></div>
                                <button class="btn btn-sm text-danger p-0 ms-2" @click="removeItem(index)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="input-group input-group-sm w-auto border rounded">
                                    <button class="btn btn-light btn-sm px-2 text-secondary" @click="updateQty(index, -1)"><i class="fas fa-minus small"></i></button>
                                    <input type="text" class="form-control text-center bg-white border-0 p-0 fw-bold text-dark" style="width: 35px; height: 28px;" :value="item.qty" readonly>
                                    <button class="btn btn-light btn-sm px-2 text-secondary" @click="updateQty(index, 1)"><i class="fas fa-plus small"></i></button>
                                </div>
                                <div class="text-end">
                                    <div class="small text-muted" x-text="formatNumber(item.price) + ' /unit'"></div>
                                    <div class="fw-bold text-primary" x-text="formatNumber(item.price * item.qty)"></div>
                                </div>
                            </div>
                            <template x-if="item.category_name && item.category_name.includes('แก๊ส')">
                                <div class="mt-2 pt-2 border-top">
                                    <select x-model="item.gas_status" class="form-select form-select-sm bg-light border-0 text-primary fw-bold" style="font-size: 0.85rem;">
                                        <option value="">- สถานะถัง -</option>
                                        <option value="refill">🔄 หมุนเวียน</option>
                                        <option value="new">🆕 ถังใหม่</option>
                                        <option value="deposit">📦 ฝากเติม</option>
                                        <option value="borrow">🤝 ยืมถัง</option>
                                    </select>
                                </div>
                            </template>
                        </div>
                    </template>
                    <div x-show="cart.length === 0" class="text-center py-5 text-muted d-flex flex-column align-items-center justify-content-center h-100">
                        <i class="fas fa-shopping-basket fa-4x mb-3 opacity-25"></i>
                        <p class="mb-0">ไม่มีสินค้าในตะกร้า</p>
                    </div>
                </div>
            </div>

            <!-- Cart Footer -->
            <div class="bg-white border-top shadow-lg p-3 flex-shrink-0 position-relative" style="z-index: 50;">
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="small fw-bold text-muted mb-1">🚚 ค่าขนส่ง</label>
                        <div class="input-group input-group-sm">
                            <input type="number" x-model="deliveryFee" class="form-control text-end fw-bold" placeholder="0">
                            <span class="input-group-text bg-light px-2">฿</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="small fw-bold text-muted mb-1">🏷️ ส่วนลด</label>
                        <div class="input-group input-group-sm">
                            <input type="number" x-model="discount" class="form-control text-end fw-bold text-danger" placeholder="0">
                            <span class="input-group-text bg-light px-2">฿</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-end mb-3 border-top pt-2">
                    <div>
                        <div class="small text-muted">รวม: <span x-text="formatNumber(grandTotal)"></span></div>
                        <div class="fw-bold fs-5 text-dark lh-1">รวมทั้งหหมด</div>
                    </div>
                    <div class="text-end">
                        <h1 class="fw-bold text-primary m-0 lh-1" x-text="formatNumber(netTotal)"></h1>
                    </div>
                </div>
                <button class="btn btn-success w-100 py-3 fw-bold fs-5 shadow-sm text-uppercase d-flex justify-content-between px-4 align-items-center"
                    :disabled="cart.length === 0"
                    @click="openPaymentModal()"> <!-- ✅ เรียกฟังก์ชันเปิด Modal ใน posSystem -->
                    <span><i class="fas fa-wallet me-2"></i> จ่ายเงิน</span>
                    <span class="bg-white text-success px-2 rounded fs-6" x-text="formatNumber(netTotal)"></span>
                </button>
            </div>
        </div>

        <!-- ================= PAYMENT MODAL (รวมใน posSystem ไม่แยก x-data) ================= -->
        <div class="position-fixed top-0 start-0 w-100 h-100"
             style="z-index: 2000; display: none;"
             x-show="showModal" x-cloak>

            <!-- Backdrop -->
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-75"
                 x-transition.opacity @click="showModal = false"></div>

            <!-- Modal Content -->
            <div class="position-relative w-100 h-100 d-flex align-items-center justify-content-center p-3" style="pointer-events: none;">
                <div class="bg-white rounded-3 shadow-lg w-100 overflow-hidden"
                     style="max-width: 500px; pointer-events: auto;"
                     x-transition.scale.origin.center>

                    <div class="modal-header bg-success text-white p-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 fw-bold"><i class="fas fa-money-check-alt me-2"></i>ชำระเงิน</h5>
                        <button type="button" class="btn-close btn-close-white" @click="showModal = false"></button>
                    </div>

                    <div class="modal-body p-4 bg-light">
                        <!-- Top: Total -->
                        <div class="text-center mb-3 bg-white p-3 rounded shadow-sm border border-success border-opacity-25">
                            <small class="text-muted text-uppercase fw-bold">จำนวนเงิน</small>
                            <h1 class="display-4 fw-bold text-success m-0" x-text="formatNumber(netTotal)"></h1>
                        </div>

                        <!-- ✅ Date Picker (Transaction Date) -->
                        <div class="mb-3">
                            <label class="fw-bold small text-muted">วันที่ขาย</label>
                            <input type="datetime-local" x-model="transactionDate" class="form-control fw-bold border-secondary">
                        </div>

                        <div class="row g-3 mb-3">
                            <!-- Searchable Customer -->
                            <div class="col-12 position-relative">
                                <label class="fw-bold small text-muted">ค้นหาลูกค้า</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" placeholder="พิมพ์ชื่อ หรือ เบอร์โทร..."
                                           x-model="customerSearch" @input="searchCustomer()">
                                    <button class="btn btn-outline-secondary" type="button" x-show="customerId" @click="resetCustomer()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="list-group position-absolute w-100 shadow-lg" style="z-index: 3000; max-height: 200px; overflow-y: auto;"
                                     x-show="showCustomerDropdown">
                                    <template x-for="c in filteredCustomers" :key="c.id">
                                        <button type="button" class="list-group-item list-group-item-action" @click="selectCustomer(c)">
                                            <span class="fw-bold" x-text="c.name"></span>
                                            <span class="small text-muted" x-show="c.phone" x-text="' (' + c.phone + ')'"></span>
                                        </button>
                                    </template>
                                    <div class="list-group-item text-muted text-center" x-show="filteredCustomers.length === 0">ไม่พบข้อมูล</div>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="col-12">
                                <label class="fw-bold small text-muted">ช่องทางการชำระ</label>
                                <div class="d-flex gap-2">
                                    <button class="btn flex-fill py-2" :class="paymentMethod === 'cash' ? 'btn-success shadow' : 'btn-outline-secondary bg-white'" @click="paymentMethod = 'cash'"><i class="fas fa-money-bill me-1"></i> เงินสด</button>
                                    <button class="btn flex-fill py-2" :class="paymentMethod === 'transfer' ? 'btn-info text-white shadow' : 'btn-outline-secondary bg-white'" @click="paymentMethod = 'transfer'"><i class="fas fa-university me-1"></i> โอนธนาคาร</button>
                                    <button class="btn flex-fill py-2" :class="paymentMethod === 'unpaid' ? 'btn-warning text-dark shadow' : 'btn-outline-secondary bg-white'" @click="paymentMethod = 'unpaid'"><i class="fas fa-clock me-1"></i> ค้างชำระ</button>
                                </div>
                            </div>
                        </div>

                        <!-- Amount Input -->
                        <div class="mb-3" x-show="paymentMethod !== 'unpaid'">
                            <label class="fw-bold small text-muted">จำนวนเงินที่ได้รับ</label>
                            <input type="number" id="receivedInput" class="form-control form-control-lg text-center fw-bold fs-1 text-success border-success" x-model="receivedAmount" placeholder="0.00" @keydown.enter="submitPayment()">
                        </div>

                        <!-- Change -->
                        <div class="alert alert-warning d-flex justify-content-between align-items-center mb-0 shadow-sm" x-show="paymentMethod === 'cash'">
                            <span class="fw-bold text-uppercase">เงินทอน:</span>
                            <span class="fs-2 fw-bold" :class="changeAmount < 0 ? 'text-danger' : 'text-dark'" x-text="formatNumber(Math.max(0, changeAmount))"></span>
                        </div>
                    </div>

                    <div class="modal-footer p-3 bg-white border-top">
                        <button class="btn btn-secondary px-4 btn-lg" @click="showModal = false">ยกเลิก</button>
                        <button class="btn btn-success px-5 fw-bold btn-lg shadow" @click="submitPayment()" :disabled="paymentMethod !== 'unpaid' && changeAmount < 0">
                            ยืนยันชำระเงิน
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Print Area -->
        <div id="printable-area" class="d-none d-print-block">
            @if ($lastTransaction)
                <div class="p-3" style="width: 80mm; font-family: 'Sarabun', sans-serif; color: black;">
                    <div class="text-center mb-2">
                        <h4 class="fw-bold m-0">ร้านพีแก๊ส</h4>
                        <p class="small mb-0">โทร: 065 924 4463</p>
                        <p class="small mb-0">--------------------------------</p>
                    </div>
                    <div class="mb-2" style="font-size: 12px;">
                        <div><strong>เลขที่:</strong> {{ $lastTransaction->reference_no }}</div>
                        <div><strong>วันที่:</strong> {{ $lastTransaction->transaction_date }}</div>
                        <div><strong>ชื่อลูกค้า:</strong> {{ $lastTransaction->customer->name ?? 'ลูกค้าทั่วไป' }}</div>
                    </div>
                    <table class="table table-sm table-borderless mb-2" style="font-size: 12px;">
                        <thead><tr style="border-bottom: 1px dashed black;"><th>Item</th><th class="text-center">Qty</th><th class="text-end">Total</th></tr></thead>
                        <tbody>
                            @foreach ($lastTransaction->details as $detail)
                                <tr>
                                    <td>
                                        {{ $detail->product_name }}
                                        @if ($detail->gas_status) <br><small class="text-muted">({{ $detail->gas_status }})</small> @endif
                                    </td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-end">{{ number_format($detail->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="border-top: 1px dashed black;">
                            @if ($lastTransaction->delivery_fee > 0) <tr><td colspan="2" class="text-end">ค่าขนส่ง:</td><td class="text-end">{{ number_format($lastTransaction->delivery_fee, 2) }}</td></tr> @endif
                            @if ($lastTransaction->discount_amount > 0) <tr><td colspan="2" class="text-end">ส่วนลด:</td><td class="text-end">-{{ number_format($lastTransaction->discount_amount, 2) }}</td></tr> @endif
                            <tr class="fw-bold"><td colspan="2" class="text-end">รวมทั้งหมด:</td><td class="text-end">{{ number_format($lastTransaction->total_amount, 2) }}</td></tr>
                            <tr><td colspan="2" class="text-end">รับเงิน:</td><td class="text-end">{{ number_format($lastTransaction->received_amount, 2) }}</td></tr>
                            <tr><td colspan="2" class="text-end">เงินทอน:</td><td class="text-end">{{ number_format($lastTransaction->change_amount, 2) }}</td></tr>
                        </tfoot>
                    </table>
                    <div class="text-center mt-3 small"><p class="mb-0">ขอบคุณครับ</p></div>
                </div>
            @endif
        </div>

    </div>

    <!-- Script Logic (Centralized) -->
    <script>
        function formatNumber(num) {
            return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num || 0);
        }

        function posSystem(initialCustomers) {
            return {
                cart: JSON.parse(localStorage.getItem('pos_cart')) || [],
                deliveryFee: 0,
                discount: 0,

                // Modal State (รวมศูนย์)
                showModal: false,
                receivedAmount: '',
                paymentMethod: 'cash',
                transactionDate: '',

                // Customer State
                customers: initialCustomers,
                customerSearch: '',
                customerId: '',
                filteredCustomers: [],
                showCustomerDropdown: false,

                // Computed
                get grandTotal() { return this.cart.reduce((t, i) => t + (i.price * i.qty), 0); },
                get netTotal() { return Math.max(0, this.grandTotal + parseFloat(this.deliveryFee || 0) - parseFloat(this.discount || 0)); },
                get changeAmount() { return (parseFloat(this.receivedAmount) || 0) - this.netTotal; },

                init() {
                    this.$watch('cart', val => localStorage.setItem('pos_cart', JSON.stringify(val)));
                },

                // Cart Actions
                addToCart(id, name, price, category_name) {
                    let item = this.cart.find(i => i.id === id);
                    if (item) { item.qty++; }
                    else { this.cart.push({ id, name, price, qty: 1, category_name, gas_status: '' }); }
                },
                updateQty(index, amt) {
                    if (this.cart[index].qty + amt <= 0) this.removeItem(index);
                    else this.cart[index].qty += amt;
                },
                removeItem(index) { this.cart.splice(index, 1); },
                clearCart() { this.cart = []; this.deliveryFee = 0; this.discount = 0; },

                // Payment Modal Logic
                openPaymentModal() {
                    this.showModal = true;
                    this.receivedAmount = '';
                    this.paymentMethod = 'cash';
                    this.resetCustomer();

                    // Set Date
                    let now = new Date();
                    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                    this.transactionDate = now.toISOString().slice(0, 16);

                    setTimeout(() => document.getElementById('receivedInput')?.focus(), 100);
                },

                // Customer Logic
                searchCustomer() {
                    if (this.customerSearch === '') {
                        this.showCustomerDropdown = false;
                        return;
                    }
                    this.showCustomerDropdown = true;
                    this.filteredCustomers = this.customers.filter(c =>
                        c.name.toLowerCase().includes(this.customerSearch.toLowerCase()) ||
                        (c.phone && c.phone.includes(this.customerSearch))
                    );
                },
                selectCustomer(c) {
                    this.customerId = c.id;
                    this.customerSearch = c.name;
                    this.showCustomerDropdown = false;
                },
                resetCustomer() {
                    this.customerId = '';
                    this.customerSearch = '';
                    this.showCustomerDropdown = false;
                },

                // Submit Logic
                submitPayment() {
                    let received = parseFloat(this.receivedAmount) || 0;
                    if (this.paymentMethod !== 'unpaid' && received < this.netTotal) {
                        alert('ยอดเงินไม่พอ (Insufficient Amount)');
                        return;
                    }

                    // ✅ เรียก Livewire โดยใช้ตัวแปรใน Scope เดียวกันทั้งหมด
                    // สังเกต: เราใช้ this.cart, this.deliveryFee, this.transactionDate ได้เลย
                    Livewire.dispatch('show-loading'); // Optional: ถ้ามี loading

                    @this.checkout(
                        this.cart,
                        this.grandTotal,
                        received,
                        this.paymentMethod,
                        this.customerId || null,
                        this.deliveryFee,
                        this.discount,
                        '', // note
                        this.transactionDate // ✅ ส่งวันที่ไป
                    ).then(() => {
                        this.showModal = false;
                        this.clearCart();
                    }).catch(err => {
                        console.error(err);
                        alert('Error: ' + err);
                    });
                }
            }
        }

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('print-receipt', () => { setTimeout(() => window.print(), 500); });
        });
    </script>

    <!-- Styles -->
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .card:hover { transform: translateY(-3px); }
        @media print {
            @page { size: 80mm auto; margin: 0; }
            body * { visibility: hidden; }
            #printable-area, #printable-area * { visibility: visible; }
            #printable-area { position: absolute; left: 0; top: 0; width: 100%; }
        }
    </style>
</div>
