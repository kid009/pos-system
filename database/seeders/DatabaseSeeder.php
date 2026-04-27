<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------
        // 1. สร้าง Users และ Shop หลักสำหรับการทดสอบระบบ
        // ---------------------------------------------------
        $this->call([
            UserSeeder::class,
        ]);

        // ---------------------------------------------------
        // 2. Master Data Seeders
        // ---------------------------------------------------
        $this->call([
            MasterDataSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('✅ ระบบ POS Seed ทั้งหมดเสร็จสมบูรณ์!');
        $this->command->info('========================================');
        $this->command->info('👤 Admin: admin@pgas.com / password');
        $this->command->info('👤 Staff: staff@pgas.com / password');
        $this->command->info('');
    }
}