<x-app-layout>
    <x-slot:title>Edit Product</x-slot:title>

    <div class="max-w-2xl mx-auto space-y-6">

        <div class="flex items-center justify-between pb-2 border-b border-slate-200">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Product</h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Modifying product: <span class="font-semibold text-slate-800">{{ $product->name }}</span>
                </p>
            </div>
            <div>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Back to List</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700">Product Information</h2>
                <span class="text-[11px] font-mono text-slate-400">ID: #{{ $product->id }}</span>
            </div>

            <form action="{{ route('products.update', $product) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="p-6 space-y-6"
                  novalidate>
                @csrf
                @method('PUT')

                @include('products.partials.form', ['product' => $product])

                <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('products.index') }}"
                       class="px-4 py-2.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Cancel
                    </a>

                    <button type="submit"
                            id="submitBtn"
                            data-disable-on-submit
                            data-loading-text="Updating..."
                            data-loading-spinner="#btnSpinner"
                            class="inline-flex items-center justify-center px-5 py-2.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
                        <span id="btnText">Update Product</span>
                        <span id="btnSpinner" class="hidden ml-2">
                            <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>

    </div>

</x-app-layout>
