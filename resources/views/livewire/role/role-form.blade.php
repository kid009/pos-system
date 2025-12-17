<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
  <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

    <h2 class="text-xl font-bold mb-6 text-gray-800">
      {{ $roleId ? 'แก้ไข Role' : 'สร้าง Role ใหม่' }}
    </h2>

    <form wire:submit="save">

      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">ชื่อ Role</label>
        <input type="text" wire:model="name"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
          placeholder="เช่น Manager, Cashier">
        @error('name')
          <span class="text-red-500 text-xs italic">{{ $message }}</span>
        @enderror
      </div>

      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">กำหนดสิทธิ์ (Permissions)</label>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border p-4 rounded bg-gray-50">
          @foreach ($permissions as $permission)
            <label class="inline-flex items-center space-x-2 cursor-pointer hover:bg-white p-2 rounded transition">
              <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}"
                class="form-checkbox h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500">
              <span class="text-gray-700 text-sm">{{ $permission->name }}</span>
            </label>
          @endforeach
        </div>
      </div>

      <div class="flex items-center justify-end gap-4">
        <a href="{{ route('roles.index') }}" wire:navigate class="text-gray-500 hover:text-gray-700">ยกเลิก</a>
        <button type="submit"
          class="bg-indigo-600 hover:bg-indigo-700 text-black font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
          บันทึกข้อมูล
        </button>
      </div>
    </form>

  </div>
</div>
