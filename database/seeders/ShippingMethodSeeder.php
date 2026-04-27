<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'Kerry Express', 'is_active' => true],
            ['name' => 'Flash Express', 'is_active' => true],
            ['name' => 'J&T Express', 'is_active' => true],
            ['name' => 'DHL Express', 'is_active' => true],
            ['name' => 'Thailand Post (EMS)', 'is_active' => true],
            ['name' => 'Thailand Post (ลงทะเบียน)', 'is_active' => true],
            ['name' => 'Best Express', 'is_active' => false],
            ['name' => 'Ninja Van', 'is_active' => false],
        ];

        foreach ($methods as $method) {
            ShippingMethod::firstOrCreate(['name' => $method['name']], $method);
        }
    }
}