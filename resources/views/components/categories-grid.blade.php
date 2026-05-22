@props([
    'categories',
    'selectedCategory' => null,
    'onSelect' => null, // For Alpine.js integration
    'columns' => 4,
])

@php
$colClass = match($columns) {
    2 => 'col-6',
    3 => 'col-4',
    4 => 'col-3',
    6 => 'col-2',
    default => 'col-3',
};
@endphp

<div class="categories-grid-menu">
    <div class="row g-2">
        {{-- All Categories Option --}}
        <div class="{{ $colClass }}">
            <div @if($onSelect) @click="{{ $onSelect }}(null)" @endif
                 class="category-card {{ is_null($selectedCategory) ? 'active' : '' }} cursor-pointer p-3 rounded-3 border text-center transition-all">
                <div class="category-icon mb-2">
                    <span data-feather="grid" style="width: 32px; height: 32px;" class="text-primary"></span>
                </div>
                <div class="category-name fw-semibold small text-truncate">ทั้งหมด</div>
                @if(isset($categories))
                    <div class="category-count text-muted smaller">{{ $categories->sum('products_count') }} รายการ</div>
                @endif
            </div>
        </div>
        
        {{-- Individual Categories --}}
        @foreach($categories as $category)
            <div class="{{ $colClass }}">
                <div @if($onSelect) @click="{{ $onSelect }}('{{ $category->uuid }}')" @endif
                     class="category-card {{ $selectedCategory == $category->uuid ? 'active' : '' }} cursor-pointer p-3 rounded-3 border text-center transition-all">
                    <div class="category-icon mb-2">
                        @php
                            $icons = ['tag', 'package', 'box', 'shopping-bag', 'layers', 'grid', 'gift', 'award'];
                            $icon = $icons[$loop->index % count($icons)];
                        @endphp
                        <span data-feather="{{ $icon }}" style="width: 32px; height: 32px;" class="text-primary"></span>
                    </div>
                    <div class="category-name fw-semibold small text-truncate" title="{{ $category->name }}">
                        {{ $category->name }}
                    </div>
                    @if(isset($category->products_count))
                        <div class="category-count text-muted smaller">{{ $category->products_count }} รายการ</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

@once
    @push('styles')
        <style>
            .categories-grid-menu .category-card {
                background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                border-color: #e5e7eb !important;
                transition: all 0.2s ease;
            }
            .categories-grid-menu .category-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                border-color: #3B82F6 !important;
            }
            .categories-grid-menu .category-card.active {
                background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
                border-color: #3B82F6 !important;
                color: white;
            }
            .categories-grid-menu .category-card.active .category-icon span {
                color: white !important;
            }
            .categories-grid-menu .category-card.active .category-name,
            .categories-grid-menu .category-card.active .category-count {
                color: rgba(255, 255, 255, 0.9) !important;
            }
            .categories-grid-menu .cursor-pointer {
                cursor: pointer;
            }
            .categories-grid-menu .smaller {
                font-size: 0.75rem;
            }
        </style>
    @endpush
@endonce
