<?php

// app/Enums/ActionType.php

namespace App\Enums;

enum ActionTypeEnum: string
{
    case VIEW_ANY = 'viewAny';
    case VIEW = 'view';
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
