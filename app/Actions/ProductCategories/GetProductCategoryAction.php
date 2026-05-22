<?php

declare(strict_types=1);

namespace App\Actions\ProductCategories;

use App\Models\Category;

/**
 * Action สำหรับดึงข้อมูลหมวดหมู่สินค้าตัวเดียว
 */
class GetProductCategoryAction
{
    /**
     * ดึงข้อมูลหมวดหมู่พร้อมความสัมพันธ์
     */
    public function execute(Category $category): Category
    {
        return $category->loadCount('products');
    }
}
