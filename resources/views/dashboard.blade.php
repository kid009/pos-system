<x-app-layout title="ภาพรวมธุรกิจ (Dashboard)">

  <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-4">

    <div class="p-6 bg-white border-l-4 border-green-500 rounded-lg shadow-sm">
      <div class="flex items-center">
        <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
            </path>
          </svg>
        </div>
        <div>
          <p class="mb-1 text-sm font-medium text-gray-500">ยอดขายวันนี้</p>
          <h3 class="text-2xl font-bold text-gray-800">฿ 12,500</h3>
        </div>
      </div>
    </div>

    <div class="p-6 bg-white border-l-4 border-blue-500 rounded-lg shadow-sm">
      <div class="flex items-center">
        <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
          </svg>
        </div>
        <div>
          <p class="mb-1 text-sm font-medium text-gray-500">รอจัดส่ง (ด่วน)</p>
          <h3 class="text-2xl font-bold text-gray-800">5 รายการ</h3>
        </div>
      </div>
    </div>

    <div class="p-6 bg-white border-l-4 border-red-500 rounded-lg shadow-sm">
      <div class="flex items-center">
        <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
            </path>
          </svg>
        </div>
        <div>
          <p class="mb-1 text-sm font-medium text-gray-500">สินค้าใกล้หมด</p>
          <h3 class="text-2xl font-bold text-gray-800">3 รายการ</h3>
        </div>
      </div>
    </div>

    <div class="p-6 bg-white border-l-4 border-orange-500 rounded-lg shadow-sm">
      <div class="flex items-center">
        <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z">
            </path>
          </svg>
        </div>
        <div>
          <p class="mb-1 text-sm font-medium text-gray-500">ลูกค้าสมาชิก</p>
          <h3 class="text-2xl font-bold text-gray-800">142 คน</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    <div class="overflow-hidden bg-white rounded-lg shadow-sm lg:col-span-2">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">รายการขายล่าสุด</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
          <thead class="bg-gray-50 text-gray-700 uppercase font-semibold">
            <tr>
              <th class="px-6 py-3">เลขที่บิล</th>
              <th class="px-6 py-3">ลูกค้า</th>
              <th class="px-6 py-3">สถานะ</th>
              <th class="px-6 py-3">ยอดรวม</th>
              <th class="px-6 py-3">จัดการ</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 font-medium text-gray-900">INV-00124</td>
              <td class="px-6 py-4">ร้านป้าแจ๋ว (ตามสั่ง)</td>
              <td class="px-6 py-4">
                <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">จัดส่งแล้ว</span>
              </td>
              <td class="px-6 py-4">฿ 980</td>
              <td class="px-6 py-4 text-indigo-600 cursor-pointer hover:underline">ดูรายละเอียด</td>
            </tr>
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 font-medium text-gray-900">INV-00125</td>
              <td class="px-6 py-4">คุณสมชาย (บ้านพัก)</td>
              <td class="px-6 py-4">
                <span class="px-2 py-1 text-xs font-semibold text-orange-700 bg-orange-100 rounded-full">รอส่ง</span>
              </td>
              <td class="px-6 py-4">฿ 450</td>
              <td class="px-6 py-4 text-indigo-600 cursor-pointer hover:underline">ดูรายละเอียด</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">สต็อกใกล้หมด (Low Stock)</h3>
      </div>
      <ul class="divide-y divide-gray-100">
        <li class="flex items-center justify-between px-6 py-4">
          <div class="flex items-center">
            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-xs text-gray-500">Img
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-900">แก๊ส ปตท. 15kg</p>
              <p class="text-xs text-gray-500">ถังหมุนเวียน</p>
            </div>
          </div>
          <span class="text-sm font-bold text-red-600">เหลือ 2 ถัง</span>
        </li>
        <li class="flex items-center justify-between px-6 py-4">
          <div class="flex items-center">
            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-xs text-gray-500">Img
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-900">หัวปรับแรงดันต่ำ</p>
              <p class="text-xs text-gray-500">อุปกรณ์</p>
            </div>
          </div>
          <span class="text-sm font-bold text-orange-600">เหลือ 5 ชิ้น</span>
        </li>
      </ul>
      <div class="p-4 border-t border-gray-100 text-center">
        <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">ดูสต็อกทั้งหมด &rarr;</a>
      </div>
    </div>

  </div>
</x-app-layout>
