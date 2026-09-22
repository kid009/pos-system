<?php

declare(strict_types=1);

namespace App\Actions\ProductCategory;

use App\Exceptions\RecordInUseException;
use App\Models\ProductCategory;

final class DeleteProductCategoryAction
{
    public function execute(ProductCategory $category): void
    {
        $productCount = $category->products()->count();

        if ($productCount > 0) {
            // โยน Exception ตัวกลาง พร้อมระบุข้อความเฉพาะของโมดูลนี้
            throw new RecordInUseException(
                "Cannot delete category '{$category->name}' because {$productCount} product(s) are still linked to it."
            );
        }

        $category->delete();
    }
}
