<?php

namespace App\Policies;

use App\Enums\ModuleTypeEnum;

class RolePolicy extends BasePolicy
{
    protected function getModule(): string
    {
        return ModuleTypeEnum::ROLES->value;
    }
}
