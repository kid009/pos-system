@php
    $isEdit = isset($product) && $product->exists;
    $action = $isEdit ? route('product.update', $product) : route('product.store');
@endphp

<form class="max-w-4xl mx-auto" action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Name --}}
        <div class="mb-5">
            <label for="name" class="block mb-2.5 text-sm font-medium text-heading">Product Name</label>
            <input
                type="text"
                id="name"
                name="name"
                class="@error('name') is-invalid @enderror bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
                value="{{ old('name', $product->name ?? '') }}"
            />
            @error('name')
                <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                    <span class="font-medium">{{ $message }}</span>
                </div>
            @enderror
        </div>

        {{-- Slug --}}
        <div class="mb-5">
            <label for="slug" class="block mb-2.5 text-sm font-medium text-heading">Slug</label>
            <input
                type="text"
                id="slug"
                name="slug"
                class="@error('slug') is-invalid @enderror bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
                value="{{ old('slug', $product->slug ?? '') }}"
            />
            @error('slug')
                <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                    <span class="font-medium">{{ $message }}</span>
                </div>
            @enderror
        </div>

        {{-- Category --}}
        <div class="mb-5">
            <label for="category_id" class="block mb-2.5 text-sm font-medium text-heading">Category</label>
            <select
                id="category_id"
                name="category_id"
                class="@error('category_id') is-invalid @enderror bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            >
                <option value="">-- Select Category --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                    <span class="font-medium">{{ $message }}</span>
                </div>
            @enderror
        </div>

        {{-- Brand --}}
        <div class="mb-5">
            <label for="brand" class="block mb-2.5 text-sm font-medium text-heading">Brand</label>
            <input
                type="text"
                id="brand"
                name="brand"
                class="@error('brand') is-invalid @enderror bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
                value="{{ old('brand', $product->brand ?? '') }}"
            />
            @error('brand')
                <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                    <span class="font-medium">{{ $message }}</span>
                </div>
            @enderror
        </div>

        {{-- Model Number --}}
        <div class="mb-5">
            <label for="model_number" class="block mb-2.5 text-sm font-medium text-heading">Model Number</label>
            <input
                type="text"
                id="model_number"
                name="model_number"
                class="@error('model_number') is-invalid @enderror bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
                value="{{ old('model_number', $product->model_number ?? '') }}"
            />
            @error('model_number')
                <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                    <span class="font-medium">{{ $message }}</span>
                </div>
            @enderror
        </div>

        {{-- Image --}}
        <div class="mb-5">
            <label for="image" class="block mb-2.5 text-sm font-medium text-heading">Image</label>
            <input
                type="file"
                id="image"
                name="image"
                accept="image/*"
                class="@error('image') is-invalid @enderror block w-full text-sm text-body bg-neutral-secondary-medium border border-default-medium rounded-base cursor-pointer focus:ring-brand focus:border-brand file:mr-4 file:py-2.5 file:px-4 file:rounded-l-base file:border-0 file:text-sm file:font-medium file:bg-brand file:text-white hover:file:bg-brand-strong"
            />
            @error('image')
                <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                    <span class="font-medium">{{ $message }}</span>
                </div>
            @enderror
            @if ($isEdit && $product->image_path)
                <div class="mt-3">
                    <img src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}" class="h-24 w-24 object-cover rounded-base border border-default">
                </div>
            @endif
        </div>
    </div>

    {{-- Description --}}
    <div class="mb-5">
        <label for="description" class="block mb-2.5 text-sm font-medium text-heading">Description</label>
        <textarea
            id="description"
            name="description"
            rows="4"
            class="@error('description') is-invalid @enderror bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
        >{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                <span class="font-medium">{{ $message }}</span>
            </div>
        @enderror
    </div>

    {{-- Price (create only) --}}
    @if (! $isEdit)
        <div class="mb-5 p-4 bg-neutral-secondary-soft rounded-base border border-default">
            <h3 class="text-sm font-medium text-heading mb-3">Initial Price</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="price_price" class="block mb-2.5 text-sm font-medium text-heading">Price</label>
                    <input
                        type="number"
                        step="0.01"
                        id="price_price"
                        name="price[price]"
                        class="@error('price.price') is-invalid @enderror bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
                        value="{{ old('price.price', '') }}"
                    />
                    @error('price.price')
                        <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                            <span class="font-medium">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div>
                    <label for="price_cost" class="block mb-2.5 text-sm font-medium text-heading">Cost</label>
                    <input
                        type="number"
                        step="0.01"
                        id="price_cost"
                        name="price[cost]"
                        class="@error('price.cost') is-invalid @enderror bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
                        value="{{ old('price.cost', '') }}"
                    />
                    @error('price.cost')
                        <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                            <span class="font-medium">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    @endif

    {{-- Affiliate Links --}}
    <div class="mb-5 p-4 bg-neutral-secondary-soft rounded-base border border-default"
        x-data="{
            links: {{
                json_encode(old('affiliate_links',
                $product->affiliateLinks->map(fn($link) => ['id' => $link->id, 'platform' => $link->platform,
                'affiliate_url' => $link->affiliate_url,
                'is_active' => $link->is_active])->toArray() ?? []))
            }},
            addLink() {
                this.links.push({ id: null, platform: '', affiliate_url: '', is_active: true });
            },
            removeLink(index) {
                this.links.splice(index, 1);
            }
        }">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-heading">Affiliate Links</h3>
            <button type="button" @click="addLink()"
                class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-3 py-1.5 focus:outline-none">
                + Add Link
            </button>
        </div>

        <template x-for="(link, index) in links" :key="index">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4 p-3 bg-neutral-primary rounded-base border border-default items-end">
                <input type="hidden" :name="`affiliate_links[${index}][id]`" x-model="link.id">

                <div class="md:col-span-3">
                    <label class="block mb-1.5 text-xs font-medium text-heading">Platform</label>
                    <input
                        type="text"
                        :name="`affiliate_links[${index}][platform]`"
                        x-model="link.platform"
                        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2 shadow-xs"
                        placeholder="e.g. shopee"
                    />
                </div>

                <div class="md:col-span-6">
                    <label class="block mb-1.5 text-xs font-medium text-heading">URL</label>
                    <input
                        type="url"
                        :name="`affiliate_links[${index}][affiliate_url]`"
                        x-model="link.affiliate_url"
                        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2 shadow-xs"
                        placeholder="https://..."
                    />
                </div>

                <div class="md:col-span-2 flex items-center">
                    <input type="hidden" :name="`affiliate_links[${index}][is_active]`" value="0">
                    <input
                        type="checkbox"
                        :name="`affiliate_links[${index}][is_active]`"
                        x-model="link.is_active"
                        value="1"
                        class="w-4 h-4 text-brand bg-neutral-secondary-medium border-default-medium rounded focus:ring-brand focus:ring-2"
                    />
                    <label class="ms-2 text-sm font-medium text-heading">Active</label>
                </div>

                <div class="md:col-span-1">
                    <button type="button" @click="removeLink(index)"
                        class="text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-3 py-2 focus:outline-none">
                        ×
                    </button>
                </div>
            </div>
        </template>

        <div x-show="links.length === 0" class="text-sm text-body-secondary">
            No affiliate links added.
        </div>

        @error('affiliate_links.*')
            <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                <span class="font-medium">{{ $message }}</span>
            </div>
        @enderror
    </div>

    {{-- Active --}}
    <div class="mb-5 flex items-center">
        <input type="hidden" name="is_active" value="0">
        <input
            type="checkbox"
            id="is_active"
            name="is_active"
            value="1"
            class="w-4 h-4 text-brand bg-neutral-secondary-medium border-default-medium rounded focus:ring-brand focus:ring-2"
            @checked(old('is_active', $product->is_active ?? true))
        />
        <label for="is_active" class="ms-2 text-sm font-medium text-heading">Active</label>
    </div>
    @error('is_active')
        <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
            <span class="font-medium">{{ $message }}</span>
        </div>
    @enderror

    <button type="submit"
        class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Save</button>
    <a href="{{ route('product.index') }}"
        class="text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Cancel</a>

</form>
