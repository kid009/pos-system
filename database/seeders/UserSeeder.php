<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // เคลียร์ Cache ของ Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // สร้างร้านค้า 2 ร้าน
        $shopGas = Shop::firstOrCreate(['name' => 'ร้านแก๊สเจ๊หมวย'], ['phone' => '0811111111']);
        $shopCoffee = Shop::firstOrCreate(['name' => 'ร้านกาแฟโบราณ'], ['phone' => '0822222222']);

        // ==========================================
        // 1. สร้าง Admin (ดูแลทั้งระบบ ไม่ผูกกับร้านไหนเลย)
        // ==========================================
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin', // 🎯 Gate::before จะจับที่ตรงนี้
                'is_active' => true,
            ]
        );


        // ==========================================
        // 2. สร้าง เจ้าของร้านคนที่ 1 (สมชาย - ร้านแก๊ส)
        // ==========================================
        $ownerGas = User::firstOrCreate(
            ['email' => 'owner@gas.com'],
            [
                'name' => 'Somchai Owner',
                'password' => Hash::make('password'),
                'role' => 'shop_owner',
                'is_active' => true,
            ]
        );

        // 🎯 ใช้ sync() แทน syncWithoutDetaching() เพื่อการันตีว่ามีแค่ร้านเดียวแน่นอน
        $ownerGas->shops()->sync([$shopGas->id => ['role' => 'shop_owner']]);

        // กำหนดสิทธิ์ Spatie ภายในร้านแก๊ส
        setPermissionsTeamId($shopGas->id);
        if (Role::where('name', 'Shop Owner')->exists()) {
            $ownerGas->assignRole('Shop Owner');
        }


        // ==========================================
        // 3. สร้าง เจ้าของร้านคนที่ 2 (สมศรี - ร้านกาแฟ)
        // ==========================================
        $ownerCoffee = User::firstOrCreate(
            ['email' => 'owner@coffee.com'],
            [
                'name' => 'Somsri Owner',
                'password' => Hash::make('password'),
                'role' => 'shop_owner',
                'is_active' => true,
            ]
        );

        $ownerCoffee->shops()->sync([$shopCoffee->id => ['role' => 'shop_owner']]);

        // กำหนดสิทธิ์ Spatie ภายในร้านกาแฟ
        setPermissionsTeamId($shopCoffee->id);
        if (Role::where('name', 'Shop Owner')->exists()) {
            $ownerCoffee->assignRole('Shop Owner');
        }


        // ==========================================
        // 4. สร้าง พนักงาน (Staff - ร้านแก๊ส)
        // ==========================================
        $staff = User::firstOrCreate(
            ['email' => 'staff@gas.com'],
            [
                'name' => 'Nidnoi Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => true,
            ]
        );

        $staff->shops()->sync([$shopGas->id => ['role' => 'staff']]);

        // กำหนดสิทธิ์ Spatie ภายในร้านแก๊ส
        setPermissionsTeamId($shopGas->id);
        if (Role::where('name', 'Staff')->exists()) {
            $staff->assignRole('Staff');
        }

        $this->command->info('✅ สร้างข้อมูล User (1 คน : 1 ร้าน) พร้อมกำหนดสิทธิ์ Spatie Teams สำเร็จ!');
    }
}
