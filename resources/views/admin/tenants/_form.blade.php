<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="name" class="form-label">Tenant Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $tenant->name ?? '') }}">
            @error('name')<span class='text-danger'>{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="status" class="form-label d-block">Status</label> {{-- เพิ่ม class d-block --}}
            <div class="form-check form-switch">
                {{-- 1. Hidden input สำหรับส่งค่า 'inactive' เมื่อ switch ถูกปิด --}}
                <input type="hidden" name="status" value="inactive">

                {{-- 2. Checkbox input ที่จะแสดงผลเป็น Switch --}}
                <input class="form-check-input" type="checkbox" id="statusSwitch" name="status" value="active" 
                {{-- 3. Logic สำหรับการแสดงผลว่า Switch ควรจะเปิดหรือปิด --}} 
                @if(old('status', $tenant->status ?? 'active') == 'active')
                checked
                @endif
                >
                <label class="form-check-label" for="statusSwitch">Active</label>
            </div>
        </div>
    </div>
</div>
<div class="mb-3">
    <label for="domain" class="form-label">Domain</label>
    <input type="text" class="form-control" name="domain" id="domain" value="{{ old('domain', $tenant->domain ?? '') }}"
        placeholder="e.g., my-shop (optional)">
</div>
<div class="mb-3">
    <label for="receipt_header_text" class="form-label">Receipt Header Text</label>
    <textarea name="receipt_header_text" id="receipt_header_text" class="form-control"
        rows="3">{{ old('receipt_header_text', $tenant->receipt_header_text ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label for="receipt_footer_text" class="form-label">Receipt Footer Text</label>
    <textarea name="receipt_footer_text" id="receipt_footer_text" class="form-control"
        rows="3">{{ old('receipt_footer_text', $tenant->receipt_footer_text ?? '') }}</textarea>
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('admin.tenants.index') }}" class="btn btn-secondary">Cancel</a>