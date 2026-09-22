<?php

declare(strict_types=1);

namespace App\Actions\ProductCategory;

use App\DTOs\ProductCategoryDto;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CreateProductCategoryAction
{
    /**
     * ประมวลผลการสร้างหมวดหมู่สินค้าในระดับ Domain
     *
     * @throws Throwable
     */
    public function execute(ProductCategoryDto $dto): ProductCategory
    {
        return DB::transaction(function () use ($dto) {

            $category = ProductCategory::create($dto->toArray());

            return $category;
        });
    }
}
