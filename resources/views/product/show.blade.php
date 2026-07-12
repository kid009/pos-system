<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Products / Show
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex items-start gap-6 mb-6">
                        @if ($product->image_path)
                            <img src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}" class="h-48 w-48 object-cover rounded-base border border-default">
                        @endif

                        <div class="flex-1">
                            <h3 class="text-2xl font-semibold text-heading mb-2">{{ $product->name }}</h3>
                            <p class="text-sm text-body mb-4">{{ $product->slug }}</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-heading">Category:</span>
                                    <span class="text-body">{{ $product->category?->name ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-heading">Brand:</span>
                                    <span class="text-body">{{ $product->brand ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-heading">Model Number:</span>
                                    <span class="text-body">{{ $product->model_number ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-heading">Status:</span>
                                    @if ($product->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-soft text-fg-success-strong">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-soft text-fg-danger-strong">Inactive</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-medium text-heading">Current Price:</span>
                                    <span class="text-body">{{ $product->prices->first()?->price ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-heading">Cost:</span>
                                    <span class="text-body">{{ $product->prices->first()?->cost ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-heading mb-2">Description</h4>
                        <p class="text-sm text-body whitespace-pre-line">{{ $product->description ?? '-' }}</p>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-heading mb-2">Affiliate Links</h4>
                        @if ($product->affiliateLinks->isEmpty())
                            <p class="text-sm text-body-secondary">No affiliate links.</p>
                        @else
                            <ul class="space-y-2">
                                @foreach ($product->affiliateLinks as $link)
                                    <li class="text-sm">
                                        <span class="font-medium text-heading">{{ $link->platform }}:</span>
                                        <a href="{{ $link->affiliate_url }}" target="_blank" class="text-brand hover:underline">{{ $link->affiliate_url }}</a>
                                        @if ($link->is_active)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-success-soft text-fg-success-strong ms-2">Active</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-danger-soft text-fg-danger-strong ms-2">Inactive</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <a href="{{ route('product.index') }}"
                        class="bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Back</a>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
