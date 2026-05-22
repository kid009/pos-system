<x-admin-layout>
    <x-slot name="header">Warehouses Management</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Locations & Branches</h1>

            <div class="flex items-center gap-3">
                <form action="{{ route('warehouses.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Search by name or code..."
                        class="w-56 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-lg text-gray-700 text-sm">
                        Search
                    </button>
                </form>

                <a href="{{ route('warehouses.create') }}"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
                    Add Warehouse
                </a>
            </div>
        </div>

        {{-- Alerts Handlers --}}
        @if (session('success'))
            <div class="px-4 py-3 bg-emerald-100 border border-emerald-400 text-emerald-700 rounded-lg text-sm">
                {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="px-4 py-3 bg-rose-100 border border-rose-400 text-rose-700 rounded-lg text-sm">
                {{ session('error') }}</div>
        @endif

        <x-admin.card>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Warehouse Name</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($warehouses as $warehouse)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    {{ $warehouse->code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $warehouse->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($warehouse->is_active)
                                        <x-admin.badge status="success">Active</x-admin.badge>
                                    @else
                                        <x-admin.badge status="default">Inactive</x-admin.badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
                                    <a href="{{ route('warehouses.edit', $warehouse->uuid) }}"
                                        class="text-blue-600 hover:text-blue-900">Edit</a>
                                    <form action="{{ route('warehouses.destroy', $warehouse->uuid) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Are you sure you want to permanently delete this warehouse location?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 text-sm">No warehouse
                                    locations configured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($warehouses->hasPages())
                <div class="mt-4 border-t border-gray-100 pt-4">{{ $warehouses->links() }}</div>
            @endif
        </x-admin.card>
    </div>
</x-admin-layout>
