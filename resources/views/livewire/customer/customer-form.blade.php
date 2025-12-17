<div class="max-w-3xl mx-auto bg-base-100 p-6 rounded-lg shadow border border-base-200">
    <h2 class="text-2xl font-bold mb-6">{{ $customerId ? 'แก้ไขลูกค้า' : 'เพิ่มลูกค้าใหม่' }}</h2>

    <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="form-control col-span-2 md:col-span-1">
            <label class="label font-bold">ชื่อลูกค้า <span class="text-error">*</span></label>
            <input type="text" wire:model="name" class="input input-bordered w-full" />
            @error('name') <span class="text-error text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="form-control col-span-2 md:col-span-1">
            <label class="label font-bold">รหัสลูกค้า</label>
            <input type="text" wire:model="code" class="input input-bordered w-full" />
        </div>

        <div class="form-control">
            <label class="label font-bold">เบอร์โทร</label>
            <input type="text" wire:model="phone" class="input input-bordered w-full" />
        </div>

        <div class="form-control">
            <label class="label font-bold">LINE ID</label>
            <input type="text" wire:model="line_id" class="input input-bordered w-full" />
        </div>

        <div class="form-control">
            <label class="label font-bold">ละติจูด (Lat)</label>
            <input type="text" wire:model="latitude" class="input input-bordered w-full" />
        </div>

        <div class="form-control">
            <label class="label font-bold">ลองติจูด (Long)</label>
            <input type="text" wire:model="longitude" class="input input-bordered w-full" />
        </div>

        <div class="form-control col-span-2">
            <label class="label font-bold">ที่อยู่</label>
            <textarea wire:model="address" class="textarea textarea-bordered h-20"></textarea>
        </div>

        <div class="form-control col-span-2">
            <label class="label font-bold">หมายเหตุ</label>
            <textarea wire:model="notes" class="textarea textarea-bordered h-20"></textarea>
        </div>

        <div class="col-span-2 flex justify-end gap-2 mt-4">
            <a href="{{ route('customers.index') }}" class="btn btn-ghost">ยกเลิก</a>
            <button type="submit" class="btn btn-primary text-white">บันทึก</button>
        </div>
    </form>
</div>
