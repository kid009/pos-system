<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case SHOP_OWNER = 'shop_owner';
    case STAFF = 'staff';
}


