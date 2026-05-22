<?php

declare(strict_types=1);

namespace App\Actions\MasterData\Product;

use App\DTOs\MasterData\ProductDTO;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

/**
 * Action สำหรับอัปเดตสินค้า
 */
class UpdateProductAction
{
    /**
     * อัปเดตข้อมูลสินค้า
     */
    public function execute(Product $product, ProductDTO $dto): Product
    {
        return DB::transaction(function () use ($product, $dto): Product {
            $product->update($dto->toArray());

            return $product;
        });
    }
}
