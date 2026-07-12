<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Categories / Index
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <x-alert type="success" :message="session('success')" />
        <x-alert type="error" :message="session('delete')" />
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <a href="{{ route('category.create') }}"
                        class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Create</a>

                    <div
                        class="mt-6 relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
                        <table class="w-full text-sm text-left rtl:text-right text-body">
                            <thead
                                class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-medium">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 font-medium">
                                        Slug
                                    </th>
                                    <th scope="col" class="px-6 py-3 font-medium">
                                        Parent
                                    </th>
                                    <th scope="col" class="px-6 py-3 font-medium">
                                        Sort
                                    </th>
                                    <th scope="col" class="px-6 py-3 font-medium">
                                        Active
                                    </th>
                                    <th scope="col" class="px-6 py-3 font-medium">
                                        Edit
                                    </th>
                                    <th scope="col" class="px-6 py-3 font-medium">
                                        Delete
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                <tr class="bg-neutral-primary border-b border-default">
                                    <td scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $category->slug }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $category->parent?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $category->sort_order }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($category->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-soft text-fg-success-strong">Active</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-soft text-fg-danger-strong">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('category.edit', $category) }}" class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Edit</a>
                                    </td>
                                    <td>
                                        <form action="{{ route('category.destroy', $category) }}" method="post" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
