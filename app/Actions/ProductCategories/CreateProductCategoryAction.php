<?php

declare(strict_types=1);

namespace App\Actions\ProductCategories;

use App\DTOs\ProductCategoryDTO;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

/**
 * Action สำหรับสร้างหมวดหมู่สินค้าใหม่
 */
class CreateProductCategoryAction
{
    /**
     * สร้างหมวดหมู่สินค้าใหม่
     */
    public function execute(ProductCategoryDTO $dto): Category
    {
        return DB::transaction(function () use ($dto): Category {
            $category = Category::create($dto->toArray());

            return $category;
        });
    }
}
