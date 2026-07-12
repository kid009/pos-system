<?php

namespace App\Actions\Category;

use App\DTOs\Category\CategoryDTO;
use App\Models\Category;
use Illuminate\Support\Str;

class CreateCategoryAction
{
    public function execute(CategoryDTO $dto): Category
    {
        return Category::create([
            'name' => $dto->name,
            'slug' => $dto->slug ?? $this->generateUniqueSlug($dto->name),
            'description' => $dto->description,
            'sort_order' => $dto->sortOrder ?? 0,
            'is_active' => $dto->isActive ?? true,
            'parent_id' => $dto->parentId,
        ]);
    }

    private function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
