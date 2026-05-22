<?php

namespace App\Http\Requests\MasterData\Warehouse;

use App\DTOs\WarehouseDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehouseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get the warehouse instance from route for uniqueness ignore rule
        $warehouseId = $this->route('warehouse') ? $this->route('warehouse')->id : null;

        return [
            'name'      => ['required', 'string', 'max:255'],
            'code'      => [
                'required',
                'string',
                'max:50',
                'alpha_dash', // Disallows spaces, allows alphanumeric, dashes, and underscores
                Rule::unique('warehouses', 'code')->ignore($warehouseId)
            ],
            'is_active' => ['boolean'],
        ];
    }

    public function toDTO(): WarehouseDTO
    {
        return new WarehouseDTO(
            name: $this->validated('name'),
            // Standardize code column to strict uppercase configuration
            code: strtoupper($this->validated('code')),
            isActive: $this->boolean('is_active', true)
        );
    }
}
