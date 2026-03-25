@props(['transaction' => null, 'isAlpine' => false])

<div id="print-receipt-area" class="{{ $isAlpine ? 'd-none' : '' }} d-print-block">
    <div class="text-center mb-3">
        @if($isAlpine)
            <h4 class="fw-bold mb-1" x-text="selectedShopName()"></h4>
            <div class="small" x-text="selectedShopAddress()"></div>
            <div class="small" x-show="selectedShopPhone()">โทร: <span x-text="selectedShopPhone()"></span></div>
            <div>ลูกค้า: <span x-text="selectedCustomerName()"></span></div>
            <div>--------------------------------</div>
            <div class="fw-bold">ใบเสร็จรับเงิน</div>
            <br>
            <div class="fw-bold">เลขที่: <span x-text="currentInvoiceNo"></span></div>
            <div>วันที่: <span x-text="new Date().toLocaleDateString('th-TH')"></span></div>
        @else
            <h4 class="fw-bold mb-1">{{ $transaction->shop->name ?? '-' }}</h4>
            <div class="small">{{ $transaction->shop->address ?? '-' }}</div>
            <div class="small">โทร: {{ $transaction->shop->phone ?? '-' }}</div>
            <div>ลูกค้า: {{ $transaction->customer->name ?? 'ลูกค้าทั่วไป' }}</div>
            <div>--------------------------------</div>
            <div class="fw-bold">ใบเสร็จรับเงิน</div>
            <br>
            <div class="fw-bold">เลขที่: {{ $transaction->invoice_no }}</div>
            <div>วันที่: {{ $transaction->created_at->format('d/m/Y H:i:s') }}</div>
        @endif
        <br>
    </div>

    <table class="w-100 mb-2">
        <tbody>
            @if($isAlpine)
                <template x-for="item in cart" :key="item.id">
                    <tr>
                        <td class="pb-1">
                            <div x-text="item.name"></div>
                            <div class="text-muted fw-bold"><span x-text="item.qty"></span> x <span
                                    x-text="parseFloat(item.price).toLocaleString('th-TH', {minimumFractionDigits: 2})"></span></div>
                        </td>
                        <td class="text-end align-bottom pb-1 fw-bold"
                            x-text="(item.qty * item.price).toLocaleString('th-TH', {minimumFractionDigits: 2})"></td>
                    </tr>
                </template>
            @else
                @foreach($transaction->details as $item)
                    <tr>
                        <td class="pb-1">
                            <div>{{ $item->product_name }}</div>
                            <div class="text-muted fw-bold">{{ $item->qty }} x {{ number_format($item->price, 2) }}</div>
                        </td>
                        <td class="text-end align-bottom pb-1 fw-bold">
                            {{ number_format($item->subtotal, 2) }}
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="border-top pt-2 mt-2" style="border-top: 1px dashed #000 !important;">
        @if($isAlpine)
            <div class="d-flex justify-content-between fw-bold">
                <span>รวมสินค้า:</span>
                <span x-text="cartTotal().toLocaleString('th-TH', {minimumFractionDigits: 2})"></span>
            </div>
            <div class="d-flex justify-content-between fw-bold"
                x-show="discountAmount > 0 && showDiscountOnReceipt()">
                <span>ส่วนลด:</span>
                <span
                    x-text="'-' + parseFloat(discountAmount).toLocaleString('th-TH', {minimumFractionDigits: 2})"></span>
            </div>
            <div class="d-flex justify-content-between fw-bold"
                x-show="shippingAmount > 0 && showShippingOnReceipt()">
                <span>ค่าขนส่ง:</span>
                <span x-text="parseFloat(shippingAmount).toLocaleString('th-TH', {minimumFractionDigits: 2})"></span>
            </div>
            <div class="d-flex justify-content-between fw-bold fs-6 mt-1 border-top pt-1" style="border-top: 1px solid #000 !important;">
                <span>ยอดรวมทั้งสิ้น:</span>
                <span x-text="netTotal().toLocaleString('th-TH', {minimumFractionDigits: 2})"></span>
            </div>
            <div class="d-flex justify-content-between mt-1 fw-bold">
                <span>รับเงินสด:</span>
                <span x-text="receiveAmount.toLocaleString('th-TH', {minimumFractionDigits: 2})"></span>
            </div>
            <div class="d-flex justify-content-between mt-1 fw-bold">
                <span>เงินทอน:</span>
                <span x-text="changeAmount().toLocaleString('th-TH', {minimumFractionDigits: 2})"></span>
            </div>
        @else
            <div class="d-flex justify-content-between fw-bold">
                <span>รวมสินค้า:</span>
                <span>{{ number_format($transaction->details->sum('subtotal'), 2) }}</span>
            </div>
            @if($transaction->discount_amount > 0)
                <div class="d-flex justify-content-between fw-bold">
                    <span>ส่วนลด:</span>
                    <span>-{{ number_format($transaction->discount_amount, 2) }}</span>
                </div>
            @endif
            @if($transaction->shipping_amount > 0)
                <div class="d-flex justify-content-between fw-bold">
                    <span>ค่าขนส่ง:</span>
                    <span>+{{ number_format($transaction->shipping_amount, 2) }}</span>
                </div>
            @endif
            <div class="d-flex justify-content-between fw-bold fs-6 mt-1 border-top pt-1" style="border-top: 1px solid #000 !important;">
                <span>ยอดรวมทั้งสิ้น:</span>
                <span>{{ number_format($transaction->total_amount, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mt-1 fw-bold">
                <span>รับเงินสด:</span>
                <span>{{ number_format($transaction->receive_amount, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mt-1 fw-bold">
                <span>เงินทอน:</span>
                <span>{{ number_format($transaction->change_amount, 2) }}</span>
            </div>
        @endif
    </div>

    <div class="text-center mt-4">
        <div>--------------------------------</div>
        <div>ขอบคุณที่ใช้บริการ</div>
        @if(!$isAlpine)
            <div>ผู้ทำรายการ: {{ $transaction->cashier->name ?? '-' }}</div>
        @endif
    </div>
</div>

<style>
@media print {
    @page {
        margin: 0;
        size: 58mm auto;
    }

    body * {
        visibility: hidden;
    }

    #print-receipt-area,
    #print-receipt-area * {
        visibility: visible;
        font-weight: bold !important;
        color: #000000 !important;
    }

    #print-receipt-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 58mm;
        padding: 2mm 2mm;
        font-size: 13px;
        font-family: 'Tahoma', sans-serif !important;
        line-height: 1.4;
    }

    body {
        overflow: hidden;
        margin: 0;
        padding: 0;
    }

    .btn, .d-print-none, header, nav, .sidebar {
        display: none !important;
    }
}
</style>
