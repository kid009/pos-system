<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTOs\RoleDTO;
use App\Enums\RoleTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

/**
 * Request class สำหรับ validate และ authorize การทำงานกับ Role
 */
class RoleRequest extends FormRequest
{
    /**
     * ตรวจสอบสิทธิ์การเข้าถึง
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $roleId = $this->route('role') ? $this->route('role')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($roleId),
                // ป้องกันการยุ่งกับ Super Admin ผ่าน Form
                function ($attribute, $value, $fail) {
                    if (strtolower($value) === RoleTypeEnum::SUPER_ADMIN->value) {
                        $fail('ไม่สามารถสร้างหรือใช้งานชื่อ Role สงวนของระบบได้');
                    }
                },
            ],
            'guard_name' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            // เปลี่ยนจาก ID เป็น Name เพื่อความเสถียรใน CI/CD
            'permissions.*' => ['required', 'string', 'exists:permissions,name'],
        ];
    }

    /**
     * Custom error messages
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'กรุณาระบุชื่อ Role',
            'name.unique' => 'ชื่อ Role นี้มีอยู่ในระบบแล้ว',
            'permissions.*.exists' => 'Permission :input ไม่มีอยู่ในระบบ',
        ];
    }

    /**
     * แปลงเป็น DTO
     */
    public function toDTO(): RoleDTO
    {
        return new RoleDTO(
            name: $this->validated('name'),
            permissions: $this->validated('permissions') ?? [],
        );
    }
}
