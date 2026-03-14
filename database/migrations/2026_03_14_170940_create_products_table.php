<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // ความสัมพันธ์
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            // 💡 restrictOnDelete() เพื่อป้องกันการลบหมวดหมู่ที่มีสินค้าอยู่ระดับ Database
            $table->foreignId('category_id')->nullable()->constrained('categories')->restrictOnDelete();

            // ข้อมูลสินค้า
            $table->string('name')->comment('ชื่อสินค้า');
            $table->string('sku')->nullable()->comment('รหัสสินค้า / บาร์โค้ด');
            $table->decimal('price', 10, 2)->default(0)->comment('ราคาขาย');
            $table->decimal('cost', 10, 2)->default(0)->comment('ต้นทุน');
            $table->string('unit', 50)->nullable()->comment('หน่วยนับ เช่น ถัง, ขวด, กก.');

            $table->string('image_path')->nullable()->comment('รูปภาพสินค้า');
            $table->boolean('is_active')->default(true)->comment('สถานะการขาย');

            // Audit Trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
