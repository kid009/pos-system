<?php

namespace Tests\Unit\Services\Inventory;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Inventory\StockInService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockInServiceTest extends TestCase
{
    use RefreshDatabase; // ล้าง DB ทุกครั้งที่รันเทสต์

    public function test_it_correctly_updates_stock_and_creates_movements(): void
    {
        // 1. Arrange (เตรียมข้อมูล)
        $tenant = Tenant::factory()->create();
        $branch = Branch::factory()->create(['tenant_id' => $tenant->id]);
        $user = User::factory()->create(['tenant_id' => $tenant->id, 'branch_id' => $branch->id]);
        $product = Product::factory()->create(['tenant_id' => $tenant->id]);

        $service = new StockInService();
        $purchaseData = [
            'purchase_date' => now()->toDateString(),
            'supplier_name' => 'Test Supplier',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                    'cost' => 150.00,
                ]
            ]
        ];

        // 2. Act (สั่งให้ Service ทำงาน)
        $service->handle($purchaseData, $user);

        // 3. Assert (ยืนยันผลลัพธ์)
        // 3a. ยืนยันว่ามีการสร้าง Purchase header
        $this->assertDatabaseHas('purchases', [
            'supplier_name' => 'Test Supplier',
            'total_cost' => 1500.00, // 10 * 150
        ]);
        
        // 3b. ยืนยันว่ามีการสร้าง Purchase item
        $this->assertDatabaseHas('purchase_items', [
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        // 3c. ยืนยันว่าสต็อกใน branch_product เพิ่มขึ้นอย่างถูกต้อง
        $this->assertDatabaseHas('branch_product', [
            'branch_id' => $branch->id,
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        // 3d. ยืนยันว่ามีการบันทึก Stock Movement
        $this->assertDatabaseHas('stock_movements', [
            'branch_id' => $branch->id,
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 10,
        ]);
    }

    public function it_handles_multiples_items_for_the_same_product_correctly(): void
    {
        // 1. Arrange (เตรียมข้อมูล)
        $tenant = Tenant::factory()->create();
        $branch = Branch::factory()->create(['tenant_id' => $tenant->id]);
        $user = User::factory()->create(['tenant_id' => $tenant->id, 'branch_id' => $branch->id]);
        $product = Product::factory()->create(['tenant_id' => $tenant->id]);

        $service = new StockInService();
        $purchaseData = [
            'purchase_date' => now()->toDateString(),
            'supplier_name' => 'Test Supplier',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 10, 'cost' => 150.00],
                ['product_id' => $product->id, 'quantity' => 20, 'cost' => 120.00],
                ['product_id' => $product->id, 'quantity' => 30, 'cost' => 130.00],
            ]
        ];

        // 2. Act (สั่งให้ Service ทำงาน)
        $service->handle($purchaseData, $user);

        // 3a. ยืนยันว่ามี Purchase header แค่ 1 รายการ และ total_cost ถูกต้อง
        $this->assertDatabaseCount('purchases', 1);
        $this->assertDatabaseHas('purchases', [
            'total_cost' => 7800.00, // (10*150) + (20*120) + (30*130)
        ]);

        // 3b. ยืนยันว่ามี Purchase items ถูกสร้างขึ้น 3 รายการ
        $this->assertDatabaseCount('purchase_items', 3);
        $this->assertDatabaseHas('purchase_items', [ 'quantity' => 10, 'cost' => 150.00 ]);
        $this->assertDatabaseHas('purchase_items', [ 'quantity' => 20, 'cost' => 120.00 ]);
        $this->assertDatabaseHas('purchase_items', [ 'quantity' => 30, 'cost' => 130.00 ]);

        // 3c. (สำคัญที่สุด) ยืนยันว่าสต็อกของสินค้าชิ้นนี้ เพิ่มขึ้นเป็นผลรวมทั้งหมด
        $this->assertDatabaseHas('branch_product', [
            'branch_id' => $branch->id,
            'product_id' => $product->id,
            'quantity' => 60, // 10 + 20 + 30
        ]);

        // 3d. ยืนยันว่ามี Stock Movement ถูกสร้างขึ้น 3 รายการ
        $this->assertDatabaseCount('stock_movements', 3);
        $this->assertDatabaseHas('stock_movements', [ 'product_id' => $product->id, 'quantity' => 10 ]);
        $this->assertDatabaseHas('stock_movements', [ 'product_id' => $product->id, 'quantity' => 20 ]);
        $this->assertDatabaseHas('stock_movements', [ 'product_id' => $product->id, 'quantity' => 30 ]);

    }

}