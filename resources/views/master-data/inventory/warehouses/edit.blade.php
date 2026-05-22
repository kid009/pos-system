<x-admin-layout>
    <x-slot name="header">Create Warehouse Location</x-slot>

    <div class="max-w-2xl mx-auto">
        <x-admin.card>
            <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST">
                @csrf

                @include('master-data.inventory.warehouses.partials._form')

                <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <a href="{{ route('warehouses.index') }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 rounded-lg text-sm font-medium transition-colors">Cancel</a>
                    <x-admin.button variant="primary" type="submit">Create Location</x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
</x-admin-layout>
