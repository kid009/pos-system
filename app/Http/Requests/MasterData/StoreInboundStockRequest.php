<?php

namespace App\Http\Requests\MasterData;

use App\DTOs\MasterData\InboundStockDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInboundStockRequest extends FormRequest
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
        return [
            'product_id'   => ['required', 'exists:products,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'qty'          => ['required', 'integer', 'gt:0'],
            'unit_cost'    => ['required', 'numeric', 'gte:0'],
            'shipping_fee' => ['required', 'numeric', 'gte:0'],
            'reference'    => ['nullable', 'string', 'max:255'],
        ];
    }

    public function toDTO(): InboundStockDTO
    {
        return new InboundStockDTO(
            productId: (int) $this->validated('product_id'),
            warehouseId: (int) $this->validated('warehouse_id'),
            qty: abs((int) $this->validated('qty')), // Failsafe absolute integer enforcement
            unitCost: (float) $this->validated('unit_cost'),
            shippingFee: (float) $this->validated('shipping_fee'),
            reference: $this->validated('reference')
        );
    }
}
