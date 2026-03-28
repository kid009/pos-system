@extends('layouts.app')

@section('title', 'หน้าจอขายสินค้า (POS)')

@push('styles')
    <style>
        /* บังคับเต็มจอ ซ่อนเมนูหลัก */
        body {
            overflow: hidden;
        }

        .sidebar,
        header.navbar {
            display: none !important;
        }

        main.col-md-9.ms-sm-auto.col-lg-10.px-md-4 {
            width: 100% !important;
            margin-left: 0 !important;
            padding: 0 !important;
            max-width: 100%;
        }

        /* โครงสร้างหน้าจอ POS */
        .pos-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
        }

        .pos-body {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* ฝั่งซ้าย (สินค้า) 70% */
        .product-section {
            flex: 7;
            display: flex;
            flex-direction: column;
            padding: 15px;
            overflow-y: auto;
        }

        /* ฝั่งขวา (ตะกร้า) 30% */
        .cart-section {
            flex: 3;
            background: #fff;
            border-left: 1px solid #dee2e6;
            display: flex;
            flex-direction: column;
        }

        /* กริดสินค้า */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 15px;
        }

        .product-card {
            cursor: pointer;
            transition: transform 0.1s, box-shadow 0.1s;
            border: 1px solid #e9ecef;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, .1);
            border-color: #0d6efd;
        }

        .product-img {
            height: 100px;
            object-fit: cover;
            width: 100%;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
        }

        /* หมวดหมู่ */
        .category-scroll {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            padding-bottom: 10px;
        }

        .category-scroll::-webkit-scrollbar {
            height: 6px;
        }

        .category-scroll::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 4px;
        }

        /* ------------------------------------- */
        /* CSS สำหรับจำลองหน้าจอพิมพ์ใบเสร็จ (Print) - ย้ายไปที่ partials.receipt */
        /* ------------------------------------- */
    </style>
@endpush

@section('content')
    <div x-data="posSystem({{ Js::from($products) }}, {{ Js::from($categories) }}, {{ Js::from($customers) }}, {{ Js::from($shops) }})" class="pos-container">

        <header class="bg-white shadow-sm p-3 d-flex justify-content-between align-items-center" style="z-index: 10;">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm"><span
                        data-feather="home"></span></a>
                <h4 class="mb-0 text-primary fw-bold">POS Terminal</h4>

                <select x-model="selectedShop" @change="shopChanged()"
                    class="form-select form-select-sm border-primary text-primary fw-bold" style="width: 250px;">
                    <option value="">-- กรุณาเลือกร้านค้าเพื่อเริ่มขาย --</option>
                    @foreach ($shops as $shop)
                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                    @endforeach
                </select>

                <select x-model="selectedCustomer" class="form-select form-select-sm border-success text-success fw-bold"
                    style="width: 200px;">
                    <option value="">-- ลูกค้าทั่วไป --</option>
                    <template x-for="customer in rawCustomers" :key="customer.id">
                        <option :value="customer.id" x-text="customer.name"></option>
                    </template>
                </select>
            </div>



            <div class="d-flex align-items-center gap-3">
                <div class="input-group input-group-sm" style="width: 300px;">
                    <span class="input-group-text bg-white"><span data-feather="search" style="width: 16px;"></span></span>
                    <input type="text" x-model="searchQuery" class="form-control border-start-0 ps-0"
                        placeholder="ค้นหาสินค้า (ชื่อ, บาร์โค้ด)...">
                </div>
                <div class="text-end lh-1">
                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                    <small class="text-muted"><span x-text="currentTime"></span></small>
                </div>

                <button class="btn btn-warning btn-sm fw-bold position-relative d-flex align-items-center gap-1"
                    x-show="offlineQueue.length > 0" @click="syncOfflineBills()" :disabled="isSyncing">
                    <span data-feather="wifi-off" style="width: 14px;"></span>
                    <span x-show="!isSyncing">รอซิงค์ (<span x-text="offlineQueue.length"></span>)</span>
                    <span x-show="isSyncing">กำลังซิงค์...</span>

                    <span
                        class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"
                        x-show="!isSyncing">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                </button>
            </div>



        </header>

        <div class="pos-body">

            <div class="product-section">

                <div x-show="selectedShop === ''" class="text-center text-muted mt-5 pt-5">
                    <span data-feather="store" style="width: 64px; height: 64px; opacity: 0.5;"></span>
                    <h4 class="mt-3">กรุณาเลือกร้านค้าด้านบน</h4>
                    <p>เพื่อแสดงรายการสินค้าและเริ่มการขาย</p>
                </div>

                <div class="category-filter-container mb-4" x-show="selectedShop !== ''" x-cloak>
                    <div class="d-flex overflow-auto gap-2 pb-2" style="scrollbar-width: thin;">

                        <button class="btn fw-bold text-nowrap flex-shrink-0 py-2 border"
                            style="min-width: 110px; border-radius: 12px; transition: all 0.2s;"
                            :class="selectedCategory === 'all' ? 'btn-primary shadow' :
                                'bg-white text-secondary hover-bg-light'"
                            @click="selectedCategory = 'all'">
                            <span data-feather="grid" class="mb-1 d-block mx-auto"
                                style="width: 20px; height: 20px;"></span>
                            ทั้งหมด
                        </button>

                        <template x-for="cat in shopCategories()" :key="cat.id">
                            <button class="btn fw-bold text-nowrap flex-shrink-0 py-2 border"
                                style="min-width: 110px; border-radius: 12px; transition: all 0.2s;"
                                :class="selectedCategory == cat.id ? 'btn-primary shadow' :
                                    'bg-white text-secondary hover-bg-light'"
                                @click="selectedCategory = cat.id">
                                <span data-feather="tag" class="mb-1 d-block mx-auto"
                                    style="width: 20px; height: 20px;"></span>
                                <span x-text="cat.name"></span>
                            </button>
                        </template>

                    </div>
                </div>

                <div class="product-grid" x-show="selectedShop !== ''" x-cloak>
                    <template x-for="product in filteredProducts()" :key="product.id">
                        <div class="card product-card h-100" @click="addToCart(product)">

                            <template x-if="product.image">
                                <img :src="product.image" class="product-img" loading="lazy" decoding="async"
                                    alt="Product">
                            </template>
                            <template x-if="!product.image">
                                <div class="product-img d-flex align-items-center justify-content-center text-muted">
                                    <span data-feather="box"></span>
                                </div>
                            </template>

                            <div class="card-body p-2 d-flex flex-column justify-content-between">
                                <div class="small fw-bold lh-sm mb-2 text-dark" x-text="product.name"
                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                </div>
                                <div class="d-flex justify-content-between align-items-end">
                                    <span class="text-success fw-bold"
                                        x-text="'฿' + product.price.toLocaleString('th-TH', {minimumFractionDigits: 2})"></span>
                                    <small class="text-muted" x-text="product.unit"></small>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="filteredProducts().length === 0" class="col-12 text-center text-muted py-5">
                        ไม่พบสินค้าที่ค้นหา
                    </div>
                </div>

            </div>

            <div class="cart-section">
                <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><span data-feather="shopping-cart"></span> รายการขาย</h5>
                    <button class="btn btn-sm btn-outline-danger" @click="clearCart()"
                        x-show="cart.length > 0">ล้างตะกร้า</button>
                </div>

                <div class="flex-grow-1 overflow-auto p-3">
                    <div x-show="cart.length === 0" class="text-center text-muted mt-5">
                        <span data-feather="shopping-bag" style="width: 48px; height: 48px; opacity: 0.3;"></span>
                        <p class="mt-2">ตะกร้าว่างเปล่า</p>
                    </div>

                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="border-bottom border-dashed pb-2 mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <div class="fw-bold text-truncate pe-2 text-dark" x-text="item.name"></div>
                                <div class="fw-bold text-dark"
                                    x-text="'฿' + (item.price * item.qty).toLocaleString('th-TH', {minimumFractionDigits: 2})">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-1">
                                    <span class="text-muted small">฿</span>
                                    <input type="number" step="0.01"
                                        class="form-control form-control-sm p-0 border-0 border-bottom text-muted fw-bold bg-transparent"
                                        style="width: 70px; outline: none; box-shadow: none;" x-model.number="item.price">
                                    <span class="text-muted small">/ <span x-text="item.unit"></span></span>
                                </div>

                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <button class="btn btn-outline-secondary" @click="updateQty(index, -1)">-</button>
                                    <input type="text" class="form-control text-center px-0 fw-bold"
                                        :value="item.qty" readonly>
                                    <button class="btn btn-outline-secondary" @click="updateQty(index, 1)">+</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="bg-light p-3 border-top">
                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>จำนวนรายการ</span>
                        <span class="fw-bold text-dark" x-text="totalItems() + ' รายการ'"></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <h4 class="mb-0 fw-bold text-dark">ยอดสุทธิ</h4>
                        <h2 class="mb-0 fw-bold text-success"
                            x-text="'฿' + cartTotal().toLocaleString('th-TH', {minimumFractionDigits: 2})"></h2>
                    </div>

                    <button class="btn btn-success btn-lg w-100 fw-bold py-3" @click="openCheckout()"
                        :disabled="cart.length === 0 || selectedShop === ''">
                        <span data-feather="credit-card"></span> ชำระเงิน
                    </button>
                </div>
            </div>

        </div>

        <div x-show="showCheckoutModal" style="display: none;"
            class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50" style="z-index: 1040;"></div>

        <div x-show="showCheckoutModal" style="display: none;" class="position-fixed top-50 start-50 translate-middle"
            style="z-index: 1050; width: 400px;">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">รับชำระเงิน</h5>
                    <button type="button" class="btn-close btn-close-white" @click="showCheckoutModal = false"></button>
                </div>
                <div class="card-body p-4">

                    <div class="mb-3 p-2 bg-light rounded border-start border-4 border-success">
                        <small class="text-muted d-block">ลูกค้าที่เลือก:</small>
                        <span class="fw-bold fs-5 text-success" x-text="selectedCustomerName()"></span>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">วันที่ขาย (Transaction Date)</label>
                        <input type="date" x-model="transactionDate" class="form-control form-control-lg fw-bold">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">ส่วนลด (Discount)</label>
                            <input type="number" x-model.number="discountAmount" @input="receiveAmount = netTotal()"
                                class="form-control border-danger text-danger fw-bold">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">ค่าขนส่ง (Shipping)</label>
                            <input type="number" x-model.number="shippingAmount" @input="receiveAmount = netTotal()"
                                class="form-control border-info text-info fw-bold">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span class="fs-5">ยอดรวมสินค้า</span>
                        <span class="fs-5 fw-bold text-dark"
                            x-text="'฿' + cartTotal().toLocaleString('th-TH', {minimumFractionDigits: 2})"></span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="fs-4 fw-bold">ยอดสุทธิ</span>
                        <span class="fs-3 fw-bold text-primary"
                            x-text="'฿' + netTotal().toLocaleString('th-TH', {minimumFractionDigits: 2})"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ช่องทางการชำระเงิน</label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="payment_method" id="pay_cash" value="cash"
                                x-model="paymentMethod" checked>
                            <label class="btn btn-outline-primary flex-fill" for="pay_cash">เงินสด</label>

                            <input type="radio" class="btn-check" name="payment_method" id="pay_transfer"
                                value="transfer" x-model="paymentMethod">
                            <label class="btn btn-outline-primary flex-fill" for="pay_transfer">โอนเงิน</label>

                            <input type="radio" class="btn-check" name="payment_method" id="pay_credit"
                                value="credit" x-model="paymentMethod">
                            <label class="btn btn-outline-primary flex-fill" for="pay_credit">ค้างจ่าย</label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">รับเงินสด (บาท)</label>
                        <input type="number" x-model.number="receiveAmount"
                            class="form-control form-control-lg text-end fw-bold fs-3 text-success" autofocus>
                    </div>

                    <div class="d-flex justify-content-between mb-4 border-top pt-3">
                        <span class="fs-5 fw-bold text-danger">เงินทอน</span>
                        <span class="fs-3 fw-bold text-danger"
                            x-text="'฿' + (changeAmount() > 0 ? changeAmount().toLocaleString('th-TH', {minimumFractionDigits: 2}) : '0.00')"></span>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-success btn-lg fw-bold" @click="confirmCheckout()"
                            :disabled="receiveAmount < netTotal() || isProcessing">

                            <span x-show="!isProcessing"><span data-feather="printer"></span> พิมพ์ใบเสร็จ (Print)</span>

                            <span x-show="isProcessing">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                กำลังบันทึกข้อมูล...
                            </span>
                        </button>
                        <button class="btn btn-light" @click="showCheckoutModal = false">ยกเลิก</button>
                    </div>

                </div>
            </div>
        </div>

        <x-receipt :isAlpine="true" />
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {

            // 💡 Alpine Component Definition
            Alpine.data('posSystem', (productsData, categoriesData, customersData, shopsData) => ({

                // ==========================================
                // 1. STATE VARIABLES
                // ==========================================
                selectedShop: '',
                selectedCustomer: '',
                searchQuery: '',
                selectedCategory: 'all',
                currentTime: '',
                transactionDate: new Date().toISOString().split('T')[0],
                paymentMethod: 'cash',
                receiveAmount: 0,
                discountAmount: 0,
                shippingAmount: 0,
                isProcessing: false,
                isSyncing: false,

                rawProducts: productsData,
                rawCategories: categoriesData,
                rawCustomers: customersData,
                rawShops: shopsData,

                cart: [],
                showCheckoutModal: false,
                currentInvoiceNo: '-',

                offlineQueue: [],

                // ==========================================
                // 2. LIFECYCLE (ทำงานตอนโหลดหน้าเว็บ)
                // ==========================================
                init() {
                    // ⏱ 1. ระบบนาฬิกา
                    setInterval(() => {
                        const now = new Date();
                        this.currentTime = now.toLocaleTimeString('th-TH', {
                            hour: '2-digit', minute: '2-digit', second: '2-digit'
                        });
                    }, 1000);

                    // 💾 2. กู้คืนข้อมูลจาก Local Storage
                    try {
                        const savedCart = localStorage.getItem('pos_cart');
                        if (savedCart) this.cart = JSON.parse(savedCart);

                        const savedQueue = localStorage.getItem('pos_offline_queue');
                        if (savedQueue) this.offlineQueue = JSON.parse(savedQueue);
                    } catch (e) {
                        this.cart = [];
                        this.offlineQueue = [];
                    }

                    if (localStorage.getItem('pos_shop')) this.selectedShop = localStorage.getItem('pos_shop');
                    if (localStorage.getItem('pos_customer')) this.selectedCustomer = localStorage.getItem('pos_customer');

                    // 📡 3. ดักจับการเปลี่ยนแปลง เพื่อเซฟลงเครื่อง
                    this.$watch('cart', val => localStorage.setItem('pos_cart', JSON.stringify(val)));
                    this.$watch('selectedShop', val => localStorage.setItem('pos_shop', val));
                    this.$watch('selectedCustomer', val => localStorage.setItem('pos_customer', val));
                    this.$watch('offlineQueue', val => localStorage.setItem('pos_offline_queue', JSON.stringify(val)));

                    // 🚨 4. [แก้ไขแล้ว] ดักจับสัญญาณอินเทอร์เน็ต ต้องอยู่ข้างใน init() เท่านั้น 🚨
                    window.addEventListener('online', () => {
                        if (this.offlineQueue.length > 0) {
                            console.log('🌐 อินเทอร์เน็ตกลับมาแล้ว! กำลังเริ่มซิงค์ข้อมูล...');
                            this.syncOfflineBills();
                        }
                    });
                },

                // ==========================================
                // 3. COMPUTED & FILTERS
                // ==========================================
                shopCategories() {
                    if (!this.selectedShop) return [];
                    return this.rawCategories.filter(cat => cat.shop_id.toString() === this.selectedShop.toString());
                },

                filteredProducts() {
                    if (!this.selectedShop) return [];
                    return this.rawProducts.filter(product => {
                        const matchShop = product.shop_id.toString() === this.selectedShop.toString();
                        const matchCategory = this.selectedCategory === 'all' || product.category_id.toString() === this.selectedCategory.toString();
                        const searchLower = this.searchQuery.toLowerCase();
                        const matchSearch = product.name.toLowerCase().includes(searchLower) || (product.sku && product.sku.toLowerCase().includes(searchLower));
                        return matchShop && matchCategory && matchSearch;
                    });
                },

                // ==========================================
                // 4. CART ACTIONS
                // ==========================================
                shopChanged() {
                    this.cart = [];
                    this.selectedCategory = 'all';
                    this.searchQuery = '';
                },

                addToCart(product) {
                    const index = this.cart.findIndex(item => item.id === product.id);
                    if (index > -1) {
                        this.cart[index].qty++;
                    } else {
                        this.cart.push({ ...product, qty: 1 });
                    }
                },

                updateQty(index, change) {
                    this.cart[index].qty += change;
                    if (this.cart[index].qty <= 0) {
                        this.cart.splice(index, 1);
                    }
                },

                clearCart() {
                    if (confirm('ยืนยันการล้างตะกร้าสินค้า?')) this.cart = [];
                },

                // ==========================================
                // 5. CALCULATORS
                // ==========================================
                totalItems() { return this.cart.reduce((sum, item) => sum + item.qty, 0); },
                cartTotal() { return this.cart.reduce((sum, item) => sum + (parseFloat(item.price) * item.qty), 0); },
                netTotal() { return this.cartTotal() + (parseFloat(this.shippingAmount) || 0) - (parseFloat(this.discountAmount) || 0); },
                changeAmount() { return Math.max(0, (parseFloat(this.receiveAmount) || 0) - this.netTotal()); },

                // ==========================================
                // 6. CHECKOUT & OFFLINE SYNC
                // ==========================================
                openCheckout() {
                    if (this.cart.length === 0) return;
                    this.receiveAmount = this.netTotal();
                    this.showCheckoutModal = true;
                },

                resetCheckoutState() {
                    this.showCheckoutModal = false;
                    this.cart = [];
                    this.receiveAmount = 0;
                    this.discountAmount = 0;
                    this.shippingAmount = 0;
                    this.paymentMethod = 'cash';
                    this.selectedCustomer = '';
                    this.selectedCategory = 'all';
                    this.searchQuery = '';
                    this.transactionDate = new Date().toISOString().split('T')[0];
                },

                async confirmCheckout() {
                    if (this.isProcessing) return;
                    this.isProcessing = true;

                    const payload = {
                        shop_id: this.selectedShop,
                        customer_id: this.selectedCustomer,
                        cart: this.cart,
                        receive_amount: this.receiveAmount,
                        payment_method: this.paymentMethod,
                        discount_amount: this.discountAmount,
                        shipping_amount: this.shippingAmount,
                        transaction_date: this.transactionDate
                    };

                    // ถ้าไม่มีเน็ต โยนเข้าออฟไลน์ทันที
                    if (!navigator.onLine) {
                        this.handleOfflineCheckout(payload);
                        return;
                    }

                    try {
                        const response = await fetch('{{ route('pos.checkout') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(payload)
                        });

                        const result = await response.json();

                        if (response.ok && result.success) {
                            this.currentInvoiceNo = result.invoice_no;
                            this.$nextTick(() => {
                                window.print();
                                this.resetCheckoutState();
                                this.currentInvoiceNo = '-';
                            });
                        } else {
                            alert(result.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                        }

                    } catch (error) {
                        console.warn('Network issue detected, saving to offline queue...', error);
                        this.handleOfflineCheckout(payload);
                    } finally {
                        this.isProcessing = false;
                    }
                },

                handleOfflineCheckout(payload) {
                    payload.offline_id = 'OFF-' + Date.now();
                    this.offlineQueue.push(payload);
                    this.currentInvoiceNo = payload.offline_id;

                    alert('⚠️ ไม่มีอินเทอร์เน็ต: บันทึกบิลลงเครื่องแล้ว (ระบบจะซิงค์อัตโนมัติเมื่อมีเน็ต)');

                    this.$nextTick(() => {
                        window.print();
                        this.resetCheckoutState();
                        this.currentInvoiceNo = '-';
                    });
                    this.isProcessing = false;
                },

                async syncOfflineBills() {
                    if (this.isSyncing || this.offlineQueue.length === 0) return;
                    if (!navigator.onLine) {
                        alert('ยังไม่มีการเชื่อมต่ออินเทอร์เน็ตครับ');
                        return;
                    }

                    this.isSyncing = true;
                    let successCount = 0;
                    let remainingQueue = [];

                    for (let i = 0; i < this.offlineQueue.length; i++) {
                        let payload = this.offlineQueue[i];
                        try {
                            const response = await fetch('{{ route('pos.checkout') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(payload)
                            });

                            const result = await response.json();
                            if (response.ok && result.success) {
                                successCount++;
                            } else {
                                remainingQueue.push(payload);
                            }
                        } catch (error) {
                            console.error('Sync failed:', error);
                            remainingQueue.push(payload);
                        }
                    }

                    this.offlineQueue = remainingQueue;
                    this.isSyncing = false;

                    if (successCount > 0) {
                        alert(`✅ ซิงค์ข้อมูลออฟไลน์สำเร็จจำนวน ${successCount} บิล!`);
                    } else if (remainingQueue.length > 0) {
                        alert('⚠️ ซิงค์ข้อมูลไม่สำเร็จบางส่วน กรุณาลองใหม่อีกครั้ง');
                    }
                },

                // ==========================================
                // 7. HELPER FUNCTIONS
                // ==========================================
                selectedCustomerName() {
                    const customer = this.rawCustomers.find(c => c.id.toString() === this.selectedCustomer.toString());
                    return customer ? customer.name : 'ลูกค้าทั่วไป';
                },
                selectedShopName() {
                    const shop = this.rawShops.find(s => s.id.toString() === this.selectedShop.toString());
                    return shop ? shop.name : '';
                },
                selectedShopAddress() {
                    const shop = this.rawShops.find(s => s.id.toString() === this.selectedShop.toString());
                    return shop ? shop.address : '';
                },
                selectedShopPhone() {
                    const shop = this.rawShops.find(s => s.id.toString() === this.selectedShop.toString());
                    return shop ? shop.phone : '';
                },
                showDiscountOnReceipt() {
                    const shop = this.rawShops.find(s => s.id.toString() === this.selectedShop.toString());
                    return shop ? !!shop.show_discount_on_receipt : true;
                },
                showShippingOnReceipt() {
                    const shop = this.rawShops.find(s => s.id.toString() === this.selectedShop.toString());
                    return shop ? !!shop.show_shipping_on_receipt : true;
                }

            }));
        });
    </script>
@endpush
