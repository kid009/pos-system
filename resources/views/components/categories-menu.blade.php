@props([
    'categories',
    'selectedCategory' => null,
    'orientation' => 'horizontal', // horizontal, vertical, dropdown
    'showIcons' => true,
    'showCount' => false,
    'onClick' => null, // JavaScript function name for Alpine.js
])

@php
$orientationClasses = match($orientation) {
    'horizontal' => 'd-flex overflow-auto gap-2 pb-2',
    'vertical' => 'list-group list-group-flush',
    'dropdown' => 'dropdown-menu shadow',
    default => 'd-flex overflow-auto gap-2 pb-2',
};

$itemClasses = match($orientation) {
    'horizontal' => 'btn btn-sm text-nowrap',
    'vertical' => 'list-group-item list-group-item-action d-flex justify-content-between align-items-center',
    'dropdown' => 'dropdown-item d-flex justify-content-between align-items-center',
    default => 'btn btn-sm text-nowrap',
};
@endphp

@if($orientation === 'dropdown')
    <div class="dropdown">
        <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            @if($showIcons)
                <span data-feather="grid" style="width: 16px; height: 16px;"></span>
            @endif
            <span>หมวดหมู่สินค้า</span>
        </button>
        <ul class="{{ $orientationClasses }}">
            <li>
                <a href="{{ request()->url() }}" 
                   class="{{ $itemClasses }} {{ is_null($selectedCategory) ? 'active' : '' }}">
                    <span>ทั้งหมด</span>
                    @if($showCount)
                        <span class="badge bg-secondary rounded-pill">{{ $categories->sum('products_count') }}</span>
                    @endif
                </a>
            </li>
            @foreach($categories as $category)
                <li>
                    <a href="{{ request()->url() }}?category={{ $category->uuid }}" 
                       class="{{ $itemClasses }} {{ $selectedCategory == $category->uuid ? 'active' : '' }}"
                       @if($onClick) @click.prevent="{{ $onClick }}('{{ $category->uuid }}')" @endif>
                        <span>{{ $category->name }}</span>
                        @if($showCount)
                            <span class="badge bg-secondary rounded-pill">{{ $category->products_count }}</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@else
    <div class="{{ $orientationClasses }} categories-menu categories-menu--{{ $orientation }}" style="scrollbar-width: thin;">
        @if($orientation === 'horizontal')
            <a href="{{ request()->url() }}" 
               class="{{ $itemClasses }} {{ is_null($selectedCategory) ? 'btn-primary' : 'btn-outline-secondary' }}"
               @if($onClick) @click.prevent="{{ $onClick }}(null)" @endif>
                @if($showIcons)
                    <span data-feather="grid" class="me-1" style="width: 14px; height: 14px;"></span>
                @endif
                ทั้งหมด
            </a>
            @foreach($categories as $category)
                <a href="{{ request()->url() }}?category={{ $category->uuid }}" 
                   class="{{ $itemClasses }} {{ $selectedCategory == $category->uuid ? 'btn-primary' : 'btn-outline-secondary' }}"
                   @if($onClick) @click.prevent="{{ $onClick }}('{{ $category->uuid }}')" @endif>
                    @if($showIcons)
                        <span data-feather="tag" class="me-1" style="width: 14px; height: 14px;"></span>
                    @endif
                    {{ $category->name }}
                    @if($showCount)
                        <span class="badge bg-secondary rounded-pill ms-1">{{ $category->products_count }}</span>
                    @endif
                </a>
            @endforeach
        @else
            <a href="{{ request()->url() }}" 
               class="{{ $itemClasses }} {{ is_null($selectedCategory) ? 'active' : '' }}"
               @if($onClick) @click.prevent="{{ $onClick }}(null)" @endif>
                <span>ทั้งหมด</span>
                @if($showCount)
                    <span class="badge bg-primary rounded-pill">{{ $categories->sum('products_count') }}</span>
                @endif
            </a>
            @foreach($categories as $category)
                <a href="{{ request()->url() }}?category={{ $category->uuid }}" 
                   class="{{ $itemClasses }} {{ $selectedCategory == $category->uuid ? 'active' : '' }}"
                   @if($onClick) @click.prevent="{{ $onClick }}('{{ $category->uuid }}')" @endif>
                    <span>{{ $category->name }}</span>
                    @if($showCount)
                        <span class="badge bg-secondary rounded-pill">{{ $category->products_count }}</span>
                    @endif
                </a>
            @endforeach
        @endif
    </div>
@endif

@once
    @push('styles')
        <style>
            .categories-menu--horizontal {
                scrollbar-width: thin;
                scrollbar-color: #ccc transparent;
            }
            .categories-menu--horizontal::-webkit-scrollbar {
                height: 6px;
            }
            .categories-menu--horizontal::-webkit-scrollbar-thumb {
                background-color: #ccc;
                border-radius: 4px;
            }
            .categories-menu--vertical .list-group-item.active {
                background-color: #3B82F6;
                border-color: #3B82F6;
            }
        </style>
    @endpush
@endonce
