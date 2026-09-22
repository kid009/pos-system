<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryMap = ProductCategory::all()->keyBy('name');

        $products = [
            // LPG Gas Cylinders
            ['name' => '4kg LPG Cylinder', 'category' => 'LPG Gas Cylinders', 'price' => 1200.00, 'is_active' => true],
            ['name' => '9kg LPG Cylinder', 'category' => 'LPG Gas Cylinders', 'price' => 2100.00, 'is_active' => true],
            ['name' => '15kg LPG Cylinder', 'category' => 'LPG Gas Cylinders', 'price' => 3200.00, 'is_active' => true],
            ['name' => '48kg LPG Cylinder', 'category' => 'LPG Gas Cylinders', 'price' => 8500.00, 'is_active' => false],

            // Gas Regulators
            ['name' => 'Low Pressure Regulator', 'category' => 'Gas Regulators', 'price' => 180.00, 'is_active' => true],
            ['name' => 'High Pressure Regulator', 'category' => 'Gas Regulators', 'price' => 350.00, 'is_active' => true],
            ['name' => 'Auto-Cutoff Regulator', 'category' => 'Gas Regulators', 'price' => 450.00, 'is_active' => true],
            ['name' => 'Two-Stage Regulator', 'category' => 'Gas Regulators', 'price' => 620.00, 'is_active' => true],

            // Gas Hoses
            ['name' => '1.5m Gas Hose', 'category' => 'Gas Hoses', 'price' => 75.00, 'is_active' => true],
            ['name' => '3m Gas Hose', 'category' => 'Gas Hoses', 'price' => 120.00, 'is_active' => true],
            ['name' => 'Reinforced Gas Hose', 'category' => 'Gas Hoses', 'price' => 160.00, 'is_active' => true],
            ['name' => 'Flexible Hose Connector', 'category' => 'Gas Hoses', 'price' => 95.00, 'is_active' => false],

            // Gas Stoves
            ['name' => 'Single Burner Stove', 'category' => 'Gas Stoves', 'price' => 550.00, 'is_active' => true],
            ['name' => 'Double Burner Stove', 'category' => 'Gas Stoves', 'price' => 1200.00, 'is_active' => true],
            ['name' => 'Portable Camping Stove', 'category' => 'Gas Stoves', 'price' => 890.00, 'is_active' => true],
            ['name' => 'Infrared Gas Stove', 'category' => 'Gas Stoves', 'price' => 1500.00, 'is_active' => true],

            // Accessories
            ['name' => 'Gas Lighter', 'category' => 'Accessories', 'price' => 35.00, 'is_active' => true],
            ['name' => 'Hose Clamp Set', 'category' => 'Accessories', 'price' => 60.00, 'is_active' => true],
            ['name' => 'Leak Detection Solution', 'category' => 'Accessories', 'price' => 85.00, 'is_active' => true],
            ['name' => 'Cylinder Wrench', 'category' => 'Accessories', 'price' => 120.00, 'is_active' => false],
        ];

        foreach ($products as $product) {
            $category = $categoryMap->get($product['category']);

            if ($category === null) {
                continue;
            }

            Product::firstOrCreate(
                ['name' => $product['name']],
                [
                    'product_category_id' => $category->id,
                    'description' => "Sample {$product['name']} for testing purposes.",
                    'price' => $product['price'],
                    'image' => null,
                    'link_affiliate' => null,
                    'is_active' => $product['is_active'],
                ]
            );
        }
    }
}
