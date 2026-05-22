<x-admin-layout>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Edit Product: <span
                    class="text-blue-600">{{ $product->name }}</span></h1>
            <a href="{{ route('product.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>

        {{-- Form Card --}}
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="p-6">
                    <form action="{{ route('product.update', $product->uuid) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @include('master-data.product.partials._form')

                        <hr class="my-6 border-gray-200">

                        <div class="flex items-center justify-end gap-3">
                            <p class="text-sm text-gray-500">
                                update: {{ $product->updated_at->format('d/m/Y H:i') }}
                            </p>
                            <a href="{{ route('product.index') }}"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
