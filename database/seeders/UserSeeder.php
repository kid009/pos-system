<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- 1. สร้าง Super Admin ---
        // ผู้ใช้งานระดับสูงสุด ไม่ได้สังกัด Tenant หรือ Branch ใด
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@app.com',
            'password' => Hash::make('password'), // password คือ 'password'
            'tenant_id' => null,
            'branch_id' => null,
        ]);
        $superAdmin->assignRole('super-admin');


        // --- 2. สร้างข้อมูลร้านค้า (Tenant) และสาขา (Branch) สำหรับทดสอบ ---
        $tenantCoffee = Tenant::create([
            'name' => 'Khao Yai Coffee',
            'created_by' => $superAdmin->id,
            'updated_by' => $superAdmin->id,
        ]);

        $branchPakChong = Branch::create([
            'tenant_id' => $tenantCoffee->id,
            'name' => 'สาขาปากช่อง',
            'is_main' => true,
            'created_by' => $superAdmin->id,
            'updated_by' => $superAdmin->id,
        ]);

        $branchMuSi = Branch::create([
            'tenant_id' => $tenantCoffee->id,
            'name' => 'สาขาหมูสี',
            'created_by' => $superAdmin->id,
            'updated_by' => $superAdmin->id,
        ]);


        // --- 3. สร้าง Branch Manager ของร้านกาแฟ ---
        // ผู้จัดการประจำสาขาปากช่อง
        $manager = User::create([
            'name' => 'Manager PakChong',
            'email' => 'manager.pakchong@khaoyai.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenantCoffee->id,
            'branch_id' => $branchPakChong->id,
            'created_by' => $superAdmin->id,
            'updated_by' => $superAdmin->id,
        ]);
        $manager->assignRole('branch-manager');


        // --- 4. สร้าง Sales Staff ---
        // พนักงานขาย ประจำสาขาปากช่อง
        $staff1 = User::create([
            'name' => 'Staff A PakChong',
            'email' => 'staff.a.pakchong@khaoyai.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenantCoffee->id,
            'branch_id' => $branchPakChong->id,
            'created_by' => $manager->id,
            'updated_by' => $manager->id,
        ]);
        $staff1->assignRole('sales-staff');

        // พนักงานขาย ประจำสาขาหมูสี
        $staff2 = User::create([
            'name' => 'Staff B MuSi',
            'email' => 'staff.b.musi@khaoyai.com',
            'password' => Hash::make('password'),
            'tenant_id' => $tenantCoffee->id,
            'branch_id' => $branchMuSi->id,
            'created_by' => $manager->id, // สมมติว่าผู้จัดการเป็นคนสร้าง User นี้
            'updated_by' => $manager->id,
        ]);
        $staff2->assignRole('sales-staff');
    }
}
