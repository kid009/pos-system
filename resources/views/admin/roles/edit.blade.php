<x-admin-layout>
    <x-slot name="header">แก้ไขกลุ่มผู้ใช้งาน: {{ $role->name }}</x-slot>

    <form action="{{ route('roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')

        <x-admin.card class="mb-6">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">ชื่อ Role</label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                @error('name')
                    <span class="text-rose-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </x-admin.card>

        <h3 class="text-lg font-bold text-gray-800 mb-4">กำหนดสิทธิ์การใช้งาน (Permissions)</h3>

        {{-- จัด Layout แบบ Grid เพื่อให้เรียง Card สวยงาม --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach ($groupedPermissions as $moduleName => $permissions)
                @if ($permissions->isNotEmpty())
                    <x-admin.card>
                        <x-slot name="header">ระบบ: {{ strtoupper($moduleName) }}</x-slot>

                        <div class="space-y-2">
                            @foreach ($permissions as $permission)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </x-admin.card>
                @endif
            @endforeach
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('roles.index') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">ยกเลิก</a>
            <x-admin.button variant="primary" type="submit">บันทึกข้อมูล</x-admin.button>
        </div>
    </form>
</x-admin-layout>
