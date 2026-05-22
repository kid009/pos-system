<div class="space-y-5">
    {{-- Product Dropdown --}}
    <div>
        <label for="product_id" class="block text-sm font-medium text-gray-700">Select Product <span
                class="text-rose-500">*</span></label>
        <select name="product_id" id="product_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            <option value="">-- Choose Product --</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}"
                    {{ old('product_id', $movement->product_id ?? '') == $product->id ? 'selected' : '' }}>
                    {{ $product->sku }} | {{ $product->name }} (Current Stock: {{ $product->stock }})
                </option>
            @endforeach
        </select>
        @error('product_id')
            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    {{-- Warehouse Dropdown --}}
    <div>
        <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Destination Warehouse <span
                class="text-rose-500">*</span></label>
        <select name="warehouse_id" id="warehouse_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            <option value="">-- Choose Warehouse --</option>
            @foreach ($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}"
                    {{ old('warehouse_id', $movement->warehouse_id ?? '') == $warehouse->id ? 'selected' : '' }}>
                    {{ $warehouse->name }} ({{ $warehouse->code }})
                </option>
            @endforeach
        </select>
        @error('warehouse_id')
            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Quantity --}}
        <div>
            <label for="qty" class="block text-sm font-medium text-gray-700">Receive Quantity <span
                    class="text-rose-500">*</span></label>
            <input type="number" name="qty" id="qty" min="1"
                value="{{ old('qty', $movement->qty ?? '') }}" placeholder="e.g. 100"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            @error('qty')
                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Unit Purchase Cost --}}
        <div>
            <label for="unit_cost" class="block text-sm font-medium text-gray-700">Unit Cost (Excl. VAT) <span
                    class="text-rose-500">*</span></label>
            <div class="relative mt-1 rounded-md shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <span class="text-gray-500 sm:text-sm">$</span>
                </div>
                <input type="number" step="0.0001" name="unit_cost" id="unit_cost" min="0"
                    value="{{ old('unit_cost', $movement->unit_cost ?? '0.00') }}"
                    class="block w-full rounded-md border-gray-300 pl-7 focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            @error('unit_cost')
                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Shipping Fee --}}
        <div>
            <label for="shipping_fee" class="block text-sm font-medium text-gray-700">Shipping Fee Allocate</label>
            <div class="relative mt-1 rounded-md shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <span class="text-gray-500 sm:text-sm">$</span>
                </div>
                <input type="number" step="0.01" name="shipping_fee" id="shipping_fee" min="0"
                    value="{{ old('shipping_fee', '0.00') }}"
                    class="block w-full rounded-md border-gray-300 pl-7 focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            @error('shipping_fee')
                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Reference Document Notation --}}
        <div>
            <label for="reference" class="block text-sm font-medium text-gray-700">Reference Document / Note</label>
            <input type="text" name="reference" id="reference"
                value="{{ old('reference', $movement->reference ?? '') }}"
                placeholder="e.g. PO-2026-008, Supplier Memo"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            @error('reference')
                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
