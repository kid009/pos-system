@props([
    'categories',
    'selectedCategory' => null,
    'title' => 'หมวดหมู่สินค้า',
    'showCount' => true,
])

<div class="categories-sidebar-menu card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
            <span data-feather="filter" style="width: 18px; height: 18px;" class="text-primary"></span>
            {{ $title }}
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            {{-- All Categories --}}
            <a href="{{ request()->url() }}" 
               class="list-group-item list-group-item-action border-0 py-3 px-4 {{ is_null($selectedCategory) ? 'active' : '' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="d-flex align-items-center gap-2">
                        <span data-feather="grid" style="width: 16px; height: 16px;"></span>
                        <span>ทั้งหมด</span>
                    </span>
                    @if($showCount)
                        <span class="badge bg-{{ is_null($selectedCategory) ? 'light text-primary' : 'secondary' }} rounded-pill">
                            {{ $categories->sum('products_count') }}
                        </span>
                    @endif
                </div>
            </a>
            
            {{-- Category Items --}}
            @foreach($categories as $category)
                <a href="{{ request()->url() }}?category={{ $category->uuid }}" 
                   class="list-group-item list-group-item-action border-0 py-3 px-4 {{ $selectedCategory == $category->uuid ? 'active' : '' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="d-flex align-items-center gap-2">
                            @php
                                $icons = ['tag', 'package', 'box', 'shopping-bag', 'layers', 'gift'];
                                $icon = $icons[$loop->index % count($icons)];
                            @endphp
                            <span data-feather="{{ $icon }}" style="width: 16px; height: 16px;"></span>
                            <span class="text-truncate" style="max-width: 140px;" title="{{ $category->name }}">
                                {{ $category->name }}
                            </span>
                        </span>
                        @if($showCount && isset($category->products_count))
                            <span class="badge bg-{{ $selectedCategory == $category->uuid ? 'light text-primary' : 'secondary' }} rounded-pill">
                                {{ $category->products_count }}
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>

@once
    @push('styles')
        <style>
            .categories-sidebar-menu .list-group-item {
                transition: all 0.2s ease;
                border-left: 3px solid transparent !important;
            }
            .categories-sidebar-menu .list-group-item:hover {
                background-color: #f8f9fa;
                border-left-color: #3B82F6 !important;
                padding-left: 1.25rem !important;
            }
            .categories-sidebar-menu .list-group-item.active {
                background-color: #EFF6FF;
                color: #1D4ED8;
                border-left-color: #3B82F6 !important;
                font-weight: 600;
            }
            .categories-sidebar-menu .list-group-item.active span[data-feather] {
                color: #3B82F6;
            }
        </style>
    @endpush
@endonce
