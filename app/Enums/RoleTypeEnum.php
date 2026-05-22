<?php

namespace App\Enums;

enum RoleTypeEnum: string
{
    case SUPER_ADMIN = 'super_admin';
    case OWNER = 'owner';
    case EMPLOYEE = 'employee';
}
