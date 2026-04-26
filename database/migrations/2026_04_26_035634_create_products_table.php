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

            // 🚨 ผูก Foreign Key กับตารางหมวดหมู่สินค้า
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();

            $table->string('sku', 50)->unique()->comment('รหัสสินค้า / บาร์โค้ด');
            $table->string('name')->comment('ชื่อสินค้า');
            $table->decimal('price', 10, 2)->default(0)->comment('ราคาขายปลีก');
            $table->decimal('cost', 10, 2)->default(0)->comment('ต้นทุนเฉลี่ย (Moving Average)');
            $table->integer('stock_qty')->default(0)->comment('จำนวนคงเหลือ');
            $table->string('unit', 50)->nullable()->comment('หน่วยนับ เช่น ชิ้น, กล่อง');
            $table->string('image')->nullable()->comment('รูปภาพสินค้า');

            // 🌟 เพิ่มคอลัมน์สำหรับลิงก์ Affiliate
            $table->text('affiliate_link')->nullable()->comment('ลิงก์โปรโมท (Affiliate Link)');

            $table->boolean('is_active')->default(true);
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
