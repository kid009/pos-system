<x-admin-layout>
    <x-slot name="header">Stock Inbound Registry</x-slot>

    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Error Session Banner --}}
        @if (session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg text-sm">
                <strong>Failure:</strong> {{ session('error') }}
            </div>
        @endif

        <x-admin.card>
            <x-slot name="header">Record Stock Inbound Intake</x-slot>

            {{-- Double submit prevention directly at form tag via inline vanilla javascript --}}
            <form action="{{ route('inventory.inbound.store') }}" method="POST"
                onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').classList.add('opacity-50');">
                @csrf

                {{-- Step 4 Injection --}}
                @include('master-data.inventory.inbound.partials._form')

                <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <a href="{{ route('product.index') }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 rounded-lg text-sm font-medium transition-colors">
                        Cancel
                    </a>
                    <x-admin.button variant="success" type="submit">
                        Record Inbound Stock
                    </x-admin.button>
                </div>
            </form>
        </x-admin.card>
    </div>
</x-admin-layout>
