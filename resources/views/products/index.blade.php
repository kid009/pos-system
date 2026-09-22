<x-app-layout>
    <x-slot:title>Products</x-slot:title>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Products</h1>
                <p class="text-sm text-slate-500 mt-0.5">Manage catalog items and their details.</p>
            </div>
            <div>
                @can('create', \App\Models\Product::class)
                <a href="{{ route('products.create') }}"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition-colors">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add Product</span>
                </a>
                @endcan
            </div>
        </div>

        <x-search-card :action="route('products.index')" :reset-url="route('products.index')">
            <div class="sm:col-span-2 lg:col-span-3 space-y-1">
                <label for="search" class="block text-xs font-medium text-slate-600">Product Name</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="Type keywords..."
                       class="block w-full px-3 py-2 text-xs rounded-lg border border-slate-300 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="space-y-1">
                <label for="status" class="block text-xs font-medium text-slate-600">Operational Status</label>
                <select name="status" id="status"
                        class="block w-full px-3 py-2 text-xs rounded-lg border border-slate-300 bg-white text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Statuses</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </x-search-card>

        <x-table :paginator="$products">
            <x-slot:header>
                <th scope="col" class="px-6 py-3.5 w-16">#</th>
                <th scope="col" class="px-6 py-3.5 text-center">Cover</th>
                <th scope="col" class="px-6 py-3.5">Category</th>
                <th scope="col" class="px-6 py-3.5">Name</th>
                <th scope="col" class="px-6 py-3.5 text-right">Price</th>
                <th scope="col" class="px-6 py-3.5 text-center">Affiliate Link</th>
                <th scope="col" class="px-6 py-3.5 text-center">Status</th>
                <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
            </x-slot:header>

            @forelse($products as $product)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-400">
                        {{ $products->firstItem() + $loop->index }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded-lg object-cover mx-auto border border-slate-200">
                        @else
                            <div class="h-12 w-12 rounded-lg bg-slate-100 flex items-center justify-center mx-auto">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-slate-700">
                        {{ $product->category->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-slate-900">
                        {{ $product->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        {{ number_format($product->price, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($product->link_affiliate)
                            <a href="{{ $product->link_affiliate }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Link
                            </a>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <x-badge :variant="$product->is_active ? 'active' : 'inactive'">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </x-badge>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                        @can('update', $product)
                        <a href="{{ route('products.edit', $product) }}"
                           class="inline-flex p-1.5 text-amber-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-colors"
                           title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        @endcan

                        @can('delete', $product)
                        <form action="{{ route('products.destroy', $product) }}" method="POST"
                              class="inline-block" data-confirm-delete="Are you sure you want to delete this product?">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex p-1.5 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors"
                                    title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-sm text-slate-400">
                        No products found in the system.
                    </td>
                </tr>
            @endforelse
        </x-table>
    </div>
</x-app-layout>
