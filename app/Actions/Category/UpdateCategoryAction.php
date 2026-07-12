<?php

namespace App\Actions\Category;

use App\DTOs\Category\CategoryDTO;
use App\Models\Category;
use Illuminate\Support\Str;

class UpdateCategoryAction
{
    public function execute(Category $category, CategoryDTO $dto): Category
    {
        $category->update([
            'name' => $dto->name,
            'slug' => $dto->slug ?? $this->generateUniqueSlug($dto->name, $category),
            'description' => $dto->description,
            'sort_order' => $dto->sortOrder ?? 0,
            'is_active' => $dto->isActive ?? true,
            'parent_id' => $dto->parentId,
        ]);

        return $category;
    }

    private function generateUniqueSlug(string $name, Category $exclude): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Category::where('slug', $slug)->whereKeyNot($exclude->id)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
