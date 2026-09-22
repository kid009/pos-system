<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->createOrUpdateUser('Admin User', 'admin@mail.com', UserRole::ADMIN);
        $this->createOrUpdateUser('Manager User', 'manager@mail.com', UserRole::MANAGER);
        $this->createOrUpdateUser('Cashier User', 'cashier@mail.com', UserRole::CASHIER);
        $this->createOrUpdateUser('Default User', 'user@mail.com', UserRole::CASHIER);

        $this->call([
            ProductCategorySeeder::class,
            ProductSeeder::class,
        ]);
    }

    /**
     * Create or update a seeded test user.
     */
    private function createOrUpdateUser(string $name, string $email, ?UserRole $role = null): void
    {
        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
                'role' => $role,
            ]
        );
    }
}
