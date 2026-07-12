<?php

namespace App\Actions\Product;

use App\DTOs\Product\ProductDTO;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateProductAction
{
    public function execute(Product $product, ProductDTO $dto): Product
    {
        return DB::transaction(function () use ($product, $dto): Product {
            $product->update([
                'category_id' => $dto->categoryId ?? $product->category_id,
                'name' => $dto->name ?? $product->name,
                'slug' => $dto->slug ?? $this->generateUniqueSlug($dto->name ?? $product->name, $product),
                'brand' => $dto->brand ?? $product->brand,
                'model_number' => $dto->modelNumber ?? $product->model_number,
                'description' => $dto->description ?? $product->description,
                'image_path' => $dto->imagePath ?? $product->image_path,
                'is_active' => $dto->isActive ?? $product->is_active,
            ]);

            $this->syncAffiliateLinks($product, $dto);

            return $product->fresh();
        });
    }

    private function generateUniqueSlug(string $name, Product $exclude): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)->whereKeyNot($exclude->id)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function syncAffiliateLinks(Product $product, ProductDTO $dto): void
    {
        if ($dto->affiliateLinks === []) {
            return;
        }

        $existingIds = $product->affiliateLinks()->pluck('id')->toArray();
        $keptIds = [];

        foreach ($dto->affiliateLinks as $link) {
            if ($link->id !== null && in_array($link->id, $existingIds, true)) {
                $product->affiliateLinks()->whereKey($link->id)->update([
                    'platform' => $link->platform,
                    'affiliate_url' => $link->affiliateUrl,
                    'is_active' => $link->isActive ?? true,
                ]);
                $keptIds[] = $link->id;
            } else {
                $product->affiliateLinks()->create([
                    'platform' => $link->platform,
                    'affiliate_url' => $link->affiliateUrl,
                    'is_active' => $link->isActive ?? true,
                ]);
            }
        }

        $product->affiliateLinks()->whereKeyNot($keptIds)->delete();
    }
}
