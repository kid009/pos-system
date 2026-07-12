<?php

namespace App\DTOs\Category;

readonly class CategoryDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $slug = null,
        public ?string $description = null,
        public ?int $sortOrder = null,
        public ?bool $isActive = null,
        public ?int $parentId = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'sort_order' => $this->sortOrder,
            'is_active' => $this->isActive,
            'parent_id' => $this->parentId,
        ], fn ($value) => $value !== null);
    }

    public static function formRequest(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            sortOrder: isset($data['sort_order']) ? (int) $data['sort_order'] : null,
            isActive: isset($data['is_active']) ? (bool) $data['is_active'] : null,
            parentId: isset($data['parent_id']) ? (int) $data['parent_id'] : null,
        );
    }
}
