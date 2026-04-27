<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // สร้างร้านค้าเริ่มต้นถ้ายังไม่มี
        $shop = Shop::firstOrCreate(
            ['name' => 'ร้านก๊าซหุงต้ม พีแก๊ส'],
            [
                'address' => '123 ถนนสุขุมวิท แขวงคลองเตย เขตคลองเตย กรุงเทพฯ 10110',
                'phone' => '02-123-4567',
                'tax_id' => '1234567890123',
                'branch_code' => 'สำนักงานใหญ่',
                'is_active' => true,
            ]
        );

        // สร้างผู้ใช้งาน Admin
        User::firstOrCreate(
            ['email' => 'admin@pgas.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'shop_id' => $shop->id,
            ]
        );

        // สร้างผู้ใช้งาน Staff
        User::firstOrCreate(
            ['email' => 'staff@pgas.com'],
            [
                'name' => 'พนักงานขาย หน้าร้าน',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => true,
                'shop_id' => $shop->id,
            ]
        );

        $this->command->info('✅ สร้างผู้ใช้งาน Admin และ Staff เรียบร้อยแล้ว');
        $this->command->info('   Admin: admin@pgas.com / password');
        $this->command->info('   Staff: staff@pgas.com / password');
    }
}
