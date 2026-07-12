@php
    $isEdit = isset($category) && $category->exists;
    $action = $isEdit ? route('category.update', $category) : route('category.store');
@endphp

<form class="max-w-lg mx-auto" action="{{ $action }}" method="POST">
    @csrf

    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="mb-5">
        <label for="name" class="block mb-2.5 text-sm font-medium text-heading">Category Name</label>
        <input
            type="text"
            id="name"
            name="name"
            class="@error('name') is-invalid @enderror bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            value="{{ old('name', $category->name ?? '') }}"
        />
        @error('name')
            <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                <span class="font-medium">{{ $message }}</span>
            </div>
        @enderror
    </div>

    <div class="mb-5">
        <label for="description" class="block mb-2.5 text-sm font-medium text-heading">Description</label>
        <textarea
            id="description"
            name="description"
            rows="3"
            class="@error('description') is-invalid @enderror bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
        >{{ old('description', $category->description ?? '') }}</textarea>
        @error('description')
            <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                <span class="font-medium">{{ $message }}</span>
            </div>
        @enderror
    </div>

    <div class="mb-5">
        <label for="sort_order" class="block mb-2.5 text-sm font-medium text-heading">Sort Order</label>
        <input
            type="number"
            id="sort_order"
            name="sort_order"
            min="0"
            class="@error('sort_order') is-invalid @enderror bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
            value="{{ old('sort_order', $category->sort_order ?? 0) }}"
        />
        @error('sort_order')
            <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                <span class="font-medium">{{ $message }}</span>
            </div>
        @enderror
    </div>

    <div class="mb-5">
        <label for="parent_id" class="block mb-2.5 text-sm font-medium text-heading">Parent Category</label>
        <select
            id="parent_id"
            name="parent_id"
            class="@error('parent_id') is-invalid @enderror bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs"
        >
            <option value="">-- No Parent --</option>
            @foreach ($parentCategories as $parentCategory)
                <option value="{{ $parentCategory->id }}" @selected(old('parent_id', $category->parent_id) == $parentCategory->id)>
                    {{ $parentCategory->name }}
                </option>
            @endforeach
        </select>
        @error('parent_id')
            <div class="mt-2 p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                <span class="font-medium">{{ $message }}</span>
            </div>
        @enderror
    </div>

    <div class="mb-5 flex items-center">
        <input type="hidden" name="is_active" value="0">
        <input
            type="checkbox"
            id="is_active"
            name="is_active"
            value="1"
            class="w-4 h-4 text-brand bg-neutral-secondary-medium border-default-medium rounded focus:ring-brand focus:ring-2"
            @checked(old('is_active', $category->is_active ?? true))
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
    <a href="{{ route('category.index') }}"
        class="text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Cancel</a>

</form>
