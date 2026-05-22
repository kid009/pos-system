<?php

declare(strict_types=1);

namespace App\Actions\ProductCategories;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

/**
 * Action สำหรับลบ/ปิดใช้งานหมวดหมู่สินค้า (Soft Delete)
 */
class DeleteProductCategoryAction
{
    /**
     * ลบหมวดหมู่สินค้า (Soft Delete - เปลี่ยนสถานะเป็น inactive)
     *
     * @throws \Exception เมื่อหมวดหมู่มีสินค้าอยู่
     */
    public function execute(Category $category, bool $force = false): void
    {
        DB::transaction(function () use ($category, $force): void {
            // ตรวจสอบว่ามีสินค้าในหมวดหมู่หรือไม่
            if ($category->products()->exists()) {
                throw new \Exception('ไม่สามารถลบหมวดหมู่นี้ได้ เนื่องจากมีสินค้าอยู่ในหมวดหมู่');
            }

            if ($force) {
                // ลบถาวร (ใช้เมื่อไม่มีสินค้าและต้องการลบจริง)
                $categoryName = $category->name;
                $category->forceDelete();

                activity()
                    ->causedBy(auth()->user())
                    ->log("ลบหมวดหมู่สินค้าถาวร: {$categoryName}");
            } else {
                // Soft Delete - เปลี่ยนสถานะเป็น inactive
                $category->update(['is_active' => false]);

                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($category)
                    ->log("ปิดใช้งานหมวดหมู่สินค้า: {$category->name}");
            }
        });
    }
}
