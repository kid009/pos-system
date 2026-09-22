<?php

declare(strict_types=1);

namespace App\Actions\ProductCategory;

use App\DTOs\ProductCategoryDto;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use Throwable;

final class UpdateProductCategoryAction
{
    /**
     * ประมวลผลการอัปเดตหมวดหมู่สินค้า
     *
     * @throws Throwable
     */
    public function execute(ProductCategory $category, ProductCategoryDto $dto): ProductCategory
    {
        return DB::transaction(function () use ($category, $dto) {

            $category->update($dto->toArray());

            return $category;
        });
    }
}
