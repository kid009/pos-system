<div class="w-full mx-auto py-6 sm:px-6 lg:px-8">

  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">จัดการสิทธิ์ผู้ใช้งาน (Roles)</h2>
    <a href="{{ route('roles.create') }}" wire:navigate
      class="bg-indigo-600 hover:bg-indigo-700 text-black font-bold py-2 px-4 rounded shadow-lg transition transform hover:scale-105">
      + สร้าง Role ใหม่
    </a>
  </div>

  @if (session()->has('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
      <p>{{ session('success') }}</p>
    </div>
  @endif
  @if (session()->has('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
      <p>{{ session('error') }}</p>
    </div>
  @endif

  <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <table class="w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อ Role</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สิทธิ์การเข้าถึง
            (Permissions)</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">จัดการ</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @foreach ($roles as $role)
          <tr class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-bold text-gray-900">{{ $role->name }}</div>
            </td>
            <td class="px-6 py-4">
              <div class="flex flex-wrap gap-1">
                @foreach ($role->permissions->take(5) as $permission)
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    {{ $permission->name }}
                  </span>
                @endforeach
                @if ($role->permissions->count() > 5)
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                    +{{ $role->permissions->count() - 5 }} more
                  </span>
                @endif
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <a href="{{ route('roles.edit', $role->id) }}" wire:navigate
                class="text-indigo-600 hover:text-indigo-900 mr-3">แก้ไข</a>

              @if ($role->name !== 'admin' && $role->id !== 1)
                <button wire:click="delete({{ $role->id }})"
                  wire:confirm="คุณแน่ใจหรือไม่ที่จะลบ Role นี้?"
                  class="text-red-600 hover:text-red-900">
                  ลบ
                </button>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
