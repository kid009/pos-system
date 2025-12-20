<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin Account
        User::updateOrCreate(
            ['email' => 'admin@pos.com'], // เช็คจาก email ว่ามีหรือยัง
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // รหัสผ่าน default: password
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Employee Account (Cashier)
        User::updateOrCreate(
            ['email' => 'staff@pos.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // เพิ่ม Employee อีกคนเพื่อทดสอบ (Inactive)
        User::updateOrCreate(
            ['email' => 'fired@pos.com'],
            [
                'name' => 'Ex Staff',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'is_active' => false, // คนนี้ login ไม่ได้
                'email_verified_at' => now(),
            ]
        );
    }
}
