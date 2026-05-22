<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\ModuleTypeEnum;

/**
 * Policy สำหรับจัดการสิทธิ์การเข้าถึงหมวดหมู่สินค้า
 */
class ProductCategoryPolicy extends BasePolicy
{
    protected function getModule(): string
    {
        return ModuleTypeEnum::PRODUCT_CATEGORY->value;
    }
}
