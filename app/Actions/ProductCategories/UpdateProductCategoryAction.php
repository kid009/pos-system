<?php

declare(strict_types=1);

namespace App\Actions\ProductCategories;

use App\DTOs\ProductCategoryDTO;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

/**
 * Action สำหรับอัปเดตหมวดหมู่สินค้า
 */
class UpdateProductCategoryAction
{
    /**
     * อัปเดตข้อมูลหมวดหมู่สินค้า
     */
    public function execute(Category $category, ProductCategoryDTO $dto): Category
    {
        return DB::transaction(function () use ($category, $dto): Category {
            $oldData = $category->only(['name', 'is_active']);

            $category->update($dto->toArray());

            // บันทึก activity log
            activity()
                ->causedBy(auth()->user())
                ->performedOn($category)
                ->withProperties(['old' => $oldData, 'new' => $dto->toArray()])
                ->log("แก้ไขหมวดหมู่สินค้า: {$category->name}");

            return $category->fresh();
        });
    }
}
