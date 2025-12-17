<div class="w-full">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">ฐานข้อมูลลูกค้า</h2>
        <a href="{{ route('customers.create') }}" wire:navigate class="btn btn-primary text-white">+ เพิ่มลูกค้า</a>
    </div>

    <div class="mb-4">
        <label class="input input-bordered flex items-center gap-2 max-w-xs">
            <input type="text" class="grow" placeholder="ค้นหา ชื่อ, เบอร์, รหัส..."
                wire:model.live.debounce.300ms="search" />
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70">
                <path fill-rule="evenodd"
                    d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                    clip-rule="evenodd" />
            </svg>
        </label>
    </div>

    <div class="overflow-x-auto bg-base-100 shadow rounded-lg border border-base-200">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-base-200">
                    <th>รหัส</th>
                    <th>ชื่อลูกค้า</th>
                    <th>เบอร์ / LINE</th>
                    <th>พิกัด (GPS)</th>
                    <th>ผู้บันทึก</th>
                    <th class="text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                    <tr>
                        <td>{{ $c->code ?? '-' }}</td>
                        <td class="font-bold">{{ $c->name }}</td>
                        <td>
                            <div>{{ $c->phone ?? '-' }}</div>
                            <div class="text-xs text-success">{{ $c->line_id ? 'Line: ' . $c->line_id : '' }}</div>
                        </td>
                        <td>
                            @if ($c->latitude && $c->longitude)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $c->latitude }},{{ $c->longitude }}"
                                    target="_blank" class="btn btn-xs btn-outline btn-info">
                                    Map
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-xs">{{ $c->creator->name ?? '-' }}</td>
                        <td class="text-right">
                            <a href="{{ route('customers.edit', $c->id) }}" wire:navigate
                                class="btn btn-sm btn-ghost text-info">แก้ไข</a>
                            <button wire:click="delete({{ $c->id }})" wire:confirm="ยืนยันการลบ?"
                                class="btn btn-sm btn-ghost text-error">ลบ</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">ไม่พบข้อมูล</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $customers->links() }}</div>
</div>
