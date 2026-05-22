<?php

namespace App\Policies;

use App\Enums\ModuleTypeEnum;

class ProductPolicy extends BasePolicy
{
    protected function getModule(): string
    {
        return ModuleTypeEnum::PRODUCT->value;
    }
}
