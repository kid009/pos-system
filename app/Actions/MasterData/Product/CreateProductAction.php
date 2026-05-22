<?php

namespace App\Actions\MasterData\Product;

use App\DTOs\MasterData\ProductDTO;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CreateProductAction
{
    public function execute(ProductDTO $dto): Product
    {
        return DB::transaction(function () use ($dto) {

            $product = Product::create($dto->toArray());

            return $product;
        });
    }
}
