<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Shop;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ดึง Role และ Shop มารอไว้
        $roleAdmin = Role::where('name', 'admin')->first();
        $roleOwner = Role::where('name', 'shop_owner')->first();
        $roleStaff = Role::where('name', 'staff')->first();

        $shopGas = Shop::where('name', 'like', '%Gas%')->first();
        $shopCoffee = Shop::where('name', 'like', '%Coffee%')->first();

        // ==========================================
        // 1. สร้าง Super Admin (ดูแลทั้งระบบ)
        // ==========================================
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // เก็บไว้เผื่อ Backward Compatibility
            'is_active' => true,
        ]);
        // Attach Role
        $admin->roles()->attach($roleAdmin);
        // Admin อาจจะดูได้ทุกร้าน หรือไม่สังกัดร้านก็ได้ (ตาม Logic Login เรา)


        // ==========================================
        // 2. สร้าง เจ้าของร้าน (Owner) - เป็นเจ้าของร้านแก๊ส
        // ==========================================
        $owner = User::create([
            'name' => 'Somchai Owner',
            'email' => 'owner@gas.com',
            'password' => Hash::make('password'),
            'role' => 'shop_owner',
            'is_active' => true,
        ]);
        $owner->roles()->attach($roleOwner);

        // ผูกกับร้านแก๊ส (ในฐานะเจ้าของ)
        $owner->shops()->attach($shopGas->id, ['role' => 'shop_owner']);

        // (แถม) สมมติคุณสมชาย รวยมาก เป็นเจ้าของร้านกาแฟด้วย
        $owner->shops()->attach($shopCoffee->id, ['role' => 'shop_owner']);


        // ==========================================
        // 3. สร้าง พนักงาน (Staff) - อยู่ร้านแก๊ส
        // ==========================================
        $staff = User::create([
            'name' => 'Nidnoi Staff',
            'email' => 'staff@gas.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'is_active' => true,
        ]);
        $staff->roles()->attach($roleStaff);

        // ผูกกับร้านแก๊ส (ในฐานะพนักงาน)
        $staff->shops()->attach($shopGas->id, ['role' => 'staff']);
    }
}
