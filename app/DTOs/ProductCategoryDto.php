<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class ProductCategoryDto
{
    public function __construct(
        public string $name,
        public ?string $description,
        public bool $isActive = true
    ) {}

    /**
     * Factory Method สำหรับแปลง FormRequest เป็น DTO
     */
    public static function fromArray(array $data): self
    {
        // 1. Sanitize ค่า Name
        $name = trim((string) ($data['name'] ?? ''));

        // 2. Normalize Description (ถ้าเป็น Empty String ให้แปลงเป็น NULL)
        $description = isset($data['description']) && trim((string) $data['description']) !== ''
            ? trim((string) $data['description'])
            : null;

        // 3. ป้องกัน Bug จากการส่ง Boolean ในรูปแบบ Checkbox HTML ("1"/"0"), "on", หรือ boolean จริง
        $isActive = filter_var($data['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN);

        return new self(
            name: $name,
            description: $description,
            isActive: $isActive
        );
    }

    /**
     * แปลงกลับเป็น Array หากจำเป็นต้องใช้กับ Eloquent create()
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->isActive,
        ];
    }
}
