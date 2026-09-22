<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\DTOs\ProductDto;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Throwable;

final class UpdateProductAction
{
    /**
     * ประมวลผลการอัปเดตสินค้า
     *
     * @throws Throwable
     */
    public function execute(Product $product, ProductDto $dto): Product
    {
        return DB::transaction(function () use ($product, $dto) {
            $data = $dto->toArray();

            if ($dto->image !== null) {
                $data['image'] = $dto->image->store('products', 'public');
            }

            $product->update($data);

            return $product;
        });
    }
}
