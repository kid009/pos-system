<?php

namespace App\Enums;

enum ModuleTypeEnum: string
{
    case PRODUCT_CATEGORY = 'product_category';
    case PRODUCT = 'product';
    case ROLES = 'roles';
    case PERMISSIONS = 'permissions';
}
