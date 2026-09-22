<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Models\Product;

final class DeleteProductAction
{
    /**
     * ประมวลผลการลบสินค้า
     */
    public function execute(Product $product): void
    {
        $product->delete();
    }
}
