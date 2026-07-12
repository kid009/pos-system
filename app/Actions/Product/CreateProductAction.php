<?php

namespace App\Actions\Product;

use App\DTOs\Product\ProductDTO;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateProductAction
{
    public function execute(ProductDTO $dto): Product
    {
        return DB::transaction(function () use ($dto): Product {
            $product = Product::create([
                'category_id' => $dto->categoryId,
                'name' => $dto->name,
                'slug' => $dto->slug ?? $this->generateUniqueSlug($dto->name),
                'brand' => $dto->brand,
                'model_number' => $dto->modelNumber,
                'description' => $dto->description,
                'image_path' => $dto->imagePath,
                'is_active' => $dto->isActive ?? true,
            ]);

            if ($dto->price !== null) {
                $product->prices()->create([
                    'price' => $dto->price->price ?? 0,
                    'cost' => $dto->price->cost ?? 0,
                    'started_at' => $dto->price->startedAt ?? now(),
                    'ended_at' => $dto->price->endedAt,
                ]);
            }

            foreach ($dto->affiliateLinks as $link) {
                $product->affiliateLinks()->create([
                    'platform' => $link->platform,
                    'affiliate_url' => $link->affiliateUrl,
                    'is_active' => $link->isActive ?? true,
                ]);
            }

            return $product;
        });
    }

    private function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
