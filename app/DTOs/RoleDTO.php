<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Data Transfer Object สำหรับ Role
 */
readonly class RoleDTO
{
    public function __construct(
        public string $name,
        public array $permissions = [],
    ) {}
}
