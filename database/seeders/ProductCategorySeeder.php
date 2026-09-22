<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'LPG Gas Cylinders',
                'description' => ' refillable liquefied petroleum gas cylinders for residential and commercial use.',
                'is_active' => true,
            ],
            [
                'name' => 'Gas Regulators',
                'description' => 'Pressure regulators and safety valves for LPG systems.',
                'is_active' => true,
            ],
            [
                'name' => 'Gas Hoses',
                'description' => 'Flexible hoses and connectors for gas appliance installations.',
                'is_active' => true,
            ],
            [
                'name' => 'Gas Stoves',
                'description' => 'Portable and built-in stoves for household cooking.',
                'is_active' => true,
            ],
            [
                'name' => 'Accessories',
                'description' => 'Miscellaneous accessories including lighters, clamps, and maintenance kits.',
                'is_active' => false,
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
