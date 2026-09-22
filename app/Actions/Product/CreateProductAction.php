<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\DTOs\ProductDto;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CreateProductAction
{
    /**
     * ประมวลผลการสร้างสินค้าในระดับ Domain
     *
     * @throws Throwable
     */
    public function execute(ProductDto $dto): Product
    {
        return DB::transaction(function () use ($dto) {
            $data = $dto->toArray();

            if ($dto->image !== null) {
                $data['image'] = $dto->image->store('products', 'public');
            }

            $product = Product::create($data);

            return $product;
        });
    }
}
