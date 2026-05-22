@props([
    'categories',
    'selectedCategory' => null,
    'onSelect' => null,
    'allText' => 'ทั้งหมด',
])

<div class="categories-pills-menu">
    <div class="d-flex flex-wrap gap-2">
        {{-- All Button --}}
        <button type="button"
                @if($onSelect) @click="{{ $onSelect }}(null)" @endif
                class="btn btn-sm rounded-pill {{ is_null($selectedCategory) ? 'btn-primary' : 'btn-outline-secondary' }}">
            {{ $allText }}
        </button>
        
        {{-- Category Pills --}}
        @foreach($categories as $category)
            <button type="button"
                    @if($onSelect) @click="{{ $onSelect }}('{{ $category->uuid }}')" @endif
                    class="btn btn-sm rounded-pill {{ $selectedCategory == $category->uuid ? 'btn-primary' : 'btn-outline-secondary' }}">
                {{ $category->name }}
            </button>
        @endforeach
    </div>
</div>

@once
    @push('styles')
        <style>
            .categories-pills-menu .btn {
                transition: all 0.2s ease;
                font-weight: 500;
            }
            .categories-pills-menu .btn-outline-secondary:hover {
                background-color: #f8f9fa;
                border-color: #3B82F6;
                color: #3B82F6;
            }
            .categories-pills-menu .btn-primary {
                background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
                border-color: #3B82F6;
            }
        </style>
    @endpush
@endonce
