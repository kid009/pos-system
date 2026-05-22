<x-admin-layout>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">
                Edit Product Category: <span class="text-blue-600">{{ $productCategory->name }}</span>
            </h1>
            <a href="{{ route('product-categories.index') }}"
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
                    <form action="{{ route('product-categories.update', $productCategory->uuid) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @include('master-data.product-category.partials._form', [
                            'productCategory' => $productCategory,
                        ])

                        <hr class="my-6 border-gray-200">

                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500">
                                update: {{ $productCategory->updated_at->format('d/m/Y H:i') }}
                            </p>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('product-categories.index') }}"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    cancel
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
