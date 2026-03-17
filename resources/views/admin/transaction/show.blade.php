@extends('layouts.app')

@section('title', 'รายละเอียดการขาย - ' . $transaction->invoice_no)

@section('content')
<div class="pt-3 pb-2 mb-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center border-bottom pb-2 mb-4">
        <h1 class="h2 mb-0">รายละเอียดการขาย #{{ $transaction->invoice_no }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                <span data-feather="arrow-left"></span> กลับ
            </a>
            <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                <span data-feather="printer"></span> พิมพ์ใบเสร็จ
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Table of Items -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold">รายการสินค้า</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4" style="width: 50px;">#</th>
                                    <th class="py-3">รายการสินค้า</th>
                                    <th class="py-3 text-center">ราคา/หน่วย</th>
                                    <th class="py-3 text-center">จำนวน</th>
                                    <th class="py-3 text-end px-4">รวม (บาท)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->details as $index => $detail)
                                <tr>
                                    <td class="px-4 text-muted small">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $detail->product_name }}</div>
                                        <div class="text-muted small">SKU: {{ $detail->product->sku ?? '-' }}</div>
                                    </td>
                                    <td class="text-center">{{ number_format($detail->price, 2) }}</td>
                                    <td class="text-center">{{ $detail->qty }}</td>
                                    <td class="text-end px-4 fw-bold">{{ number_format($detail->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end py-2">รวมยอดสินค้า</td>
                                    <td class="text-end px-4 py-2 fw-bold">{{ number_format($transaction->details->sum('subtotal'), 2) }}</td>
                                </tr>
                                @if($transaction->discount_amount > 0)
                                <tr>
                                    <td colspan="4" class="text-end py-2 text-danger">ส่วนลด (Discount)</td>
                                    <td class="text-end px-4 py-2 fw-bold text-danger">-{{ number_format($transaction->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if($transaction->shipping_amount > 0)
                                <tr>
                                    <td colspan="4" class="text-end py-2 text-info">ค่าขนส่ง (Shipping)</td>
                                    <td class="text-end px-4 py-2 fw-bold text-info">+{{ number_format($transaction->shipping_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="text-end py-3 fw-bold">ยอดรวมสุทธิ (Total Amount)</td>
                                    <td class="text-end px-4 py-3 fw-bold text-primary fs-5">
                                        {{ number_format($transaction->total_amount, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 border-end">
                            <label class="text-muted small d-block mb-1">ยอดรวม</label>
                            <h4 class="fw-bold">{{ number_format($transaction->total_amount, 2) }}</h4>
                        </div>
                        <div class="col-md-4 border-end">
                            <label class="text-muted small d-block mb-1">รับเงินมา</label>
                            <h4 class="fw-bold text-success">{{ number_format($transaction->receive_amount, 2) }}</h4>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small d-block mb-1">เงินทอน</label>
                            <h4 class="fw-bold text-danger">{{ number_format($transaction->change_amount, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Transaction Info -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold">ข้อมูลการทำรายการ</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted">เลขที่ใบเสร็จ</span>
                            <span class="fw-bold">{{ $transaction->invoice_no }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted">วันที่ทำรายการ</span>
                            <span>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted">สาขา</span>
                            <span>{{ $transaction->shop->name ?? '-' }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted">ลูกค้า</span>
                            <span class="fw-bold">{{ $transaction->customer->name ?? 'ลูกค้าทั่วไป' }}</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted">พนักงานขาย</span>
                            <span>{{ $transaction->cashier->name ?? '-' }}</span>
                        </li>
                        <li class="list-group-item px-0 border-bottom-0">
                            <form action="{{ route('transactions.update-payment-method', $transaction->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <label class="text-muted small d-block mb-1">วิธีการชำระเงิน</label>
                                <div class="input-group input-group-sm">
                                    <select name="payment_method" class="form-select border-primary fw-bold" onchange="this.form.submit()">
                                        <option value="cash" @selected($transaction->payment_method === 'cash')>เงินสด (Cash)</option>
                                        <option value="transfer" @selected($transaction->payment_method === 'transfer')>โอนเงิน (Transfer)</option>
                                        <option value="credit" @selected($transaction->payment_method === 'credit')>ค้างจ่าย (Credit)</option>
                                    </select>
                                    <button class="btn btn-primary" type="submit">บันทึก</button>
                                </div>
                            </form>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center pt-3">
                            <span class="text-muted">สถานะ</span>
                            @if ($transaction->status === 'completed')
                                <span class="badge bg-success rounded-pill px-3">สำเร็จ</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3">ยกเลิก</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Print Preview Area (Simplified for Thermal Receipt) -->
            <div class="card shadow-sm border-0 bg-light d-print-none">
                <div class="card-body text-center py-4">
                    <span data-feather="printer" style="width: 48px; height: 48px; opacity: 0.2;" class="mb-3"></span>
                    <h6>ต้องการพิมพ์ใบเสร็จย้อนหลัง?</h6>
                    <p class="small text-muted">ระบบรองรับการพิมพ์ออกเครื่องพิมพ์ความร้อน (Thermal Printer)</p>
                    <button class="btn btn-dark btn-sm w-100" onclick="window.print()">
                        พิมพ์ใบเสร็จ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Area for Thermal Receipt (Print only) -->
    <div id="print-receipt-area" class="d-none d-print-block">
        <div class="text-center mb-3">
            <h4 class="fw-bold mb-1">ร้านพีแก๊ส</h4>
            <div>สาขา: {{ $transaction->shop->name ?? '-' }}</div>
            <div>ลูกค้า: {{ $transaction->customer->name ?? 'ลูกค้าทั่วไป' }}</div>
            <br>
            <div class="fw-bold">ใบเสร็จรับเงิน (สำเนา)</div>
            <br>
            <div class="fw-bold">เลขที่: {{ $transaction->invoice_no }}</div>
            <div>วันที่: {{ $transaction->created_at->format('d/m/Y H:i:s') }}</div>
            <br>
        </div>

        <table class="w-100 mb-2">
            <tbody>
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
            </tbody>
        </table>

        <div class="border-top pt-2 mt-2" style="border-top: 1px dashed #000 !important;">
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
        </div>

        <div class="text-center mt-4">
            <div>--------------------------------</div>
            <div>ขอบคุณที่ใช้บริการ</div>
            <div>ผู้ทำรายการ: {{ $transaction->cashier->name ?? 'พนักงาน' }}</div>
        </div>
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
@endsection
