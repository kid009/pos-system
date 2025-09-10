<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General
        Menu::create(['name' => 'Dashboard', 'icon' => 'home', 'route_name' => 'dashboard', 'sequence' => 1]);

        // Admin Management
        $adminManagement = Menu::create(['name' => 'Admin Management', 'icon' => 'settings', 'permission_name' => 'role.viewAny', 'sequence' => 10]);
        Menu::create(['name' => 'Roles & Permissions', 'icon' => 'shield', 'route_name' => 'admin.roles.index', 'permission_name' => 'role.viewAny', 'parent_id' => $adminManagement->id, 'sequence' => 11]);
        Menu::create(['name' => 'User Management', 'icon' => 'users', 'route_name' => 'admin.users.index', 'permission_name' => 'user.viewAny', 'parent_id' => $adminManagement->id, 'sequence' => 12]);
        Menu::create(['name' => 'Tenant Management', 'icon' => 'briefcase', 'route_name' => 'admin.tenants.index', 'permission_name' => 'tenant.viewAny', 'parent_id' => $adminManagement->id, 'sequence' => 13]);
        Menu::create(['name' => 'Branch Management', 'icon' => 'git-branch', 'route_name' => 'admin.branches.index', 'permission_name' => 'branch.viewAny', 'parent_id' => $adminManagement->id, 'sequence' => 14]);

        // POS
        $pos = Menu::create(['name' => 'Point of Sale', 'icon' => 'monitor', 'permission_name' => 'order.create', 'sequence' => 20]);
        Menu::create(['name' => 'POS Screen', 'icon' => 'monitor', 'route_name' => 'store.pos.index', 'permission_name' => 'order.create', 'parent_id' => $pos->id, 'sequence' => 21]);

        // Store Operations
        $storeOps = Menu::create(['name' => 'Store Operations', 'icon' => 'box', 'permission_name' => 'product.viewAny', 'sequence' => 30]);
        Menu::create(['name' => 'Product Catalog', 'icon' => 'package', 'route_name' => 'store.products.index', 'permission_name' => 'product.viewAny', 'parent_id' => $storeOps->id, 'sequence' => 31]);
        Menu::create(['name' => 'Customer Management', 'icon' => 'users', 'route_name' => 'store.customers.index', 'permission_name' => 'customer.viewAny', 'parent_id' => $storeOps->id, 'sequence' => 32]);
        Menu::create(['name' => 'Inventory Mngm.', 'icon' => 'archive', 'route_name' => 'store.purchases.index', 'permission_name' => 'inventory.receive', 'parent_id' => $storeOps->id, 'sequence' => 33]);
        Menu::create(['name' => 'Expense Management', 'icon' => 'dollar-sign', 'route_name' => 'store.expenses.index', 'permission_name' => 'expense.viewAny', 'parent_id' => $storeOps->id, 'sequence' => 34]);
    }
}
