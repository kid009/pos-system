<x-admin-layout>
    <x-slot name="header">
        System Overview
    </x-slot>

    {{-- Grid สำหรับแบ่งการ์ดเป็น 3 คอลัมน์ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        <x-admin.card>
            <x-slot name="header">ยอดขายวันนี้</x-slot>
            <div class="text-3xl font-bold text-gray-900">฿15,750</div>
            <p class="text-sm mt-2"><span class="text-emerald-500 font-semibold">+12.5%</span> เทียบกับเมื่อวาน</p>
        </x-admin.card>

        <x-admin.card>
            <x-slot name="header">ออเดอร์รอดำเนินการ</x-slot>
            <div class="text-3xl font-bold text-gray-900">24</div>
            <p class="text-sm text-gray-500 mt-2">ต้องการการแพ็คจัดส่ง</p>
        </x-admin.card>

        <x-admin.card>
            <x-slot name="header">การแจ้งเตือนสต็อก</x-slot>
            <div class="text-3xl font-bold text-gray-900">5</div>
            <p class="text-sm mt-2">
                <x-admin.badge status="warning">สต็อกต่ำ</x-admin.badge> กรุณาสั่งซื้อเพิ่ม
            </p>
        </x-admin.card>

    </div>

    {{-- ตัวอย่างการใช้ Card แบบมี Footer (Action Buttons) --}}
    <x-admin.card>
        <x-slot name="header">ออเดอร์ล่าสุด</x-slot>

        <p class="text-gray-700 py-4 text-center border-b border-gray-100">
            ยังไม่มีรายการสั่งซื้อใหม่ในขณะนี้
        </p>

        <x-slot name="footer">
            <x-admin.button variant="secondary">ดูรายงานทั้งหมด</x-admin.button>
            <x-admin.button variant="primary">สร้างออเดอร์ใหม่</x-admin.button>
        </x-slot>
    </x-admin.card>

</x-admin-layout>
