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
        // 1. ตารางสินค้าหลัก (รักษาความคลีนและดัชนีเดิมของพี่กั๊กไว้)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('brand')->nullable();
            $table->string('model_number')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Composite Index สำหรับสืบค้นอย่างรวดเร็ว
            $table->index(['category_id', 'is_active']);
            $table->index(['is_active', 'created_at']);
        });

        // 2. ตารางแยกสำหรับเก็บลิงก์ Affiliate (Scalable Design)
        Schema::create('product_affiliate_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();

            // เก็บประเภทแพลตฟอร์ม เช่น 'shopee', 'lazada', 'tiktok', 'custom'
            $table->string('platform');

            // ใช้ text แทน string เพื่อรองรับ URL ยาว ๆ ที่ติดรหัส Campaign ยาวมาจากมาร์เก็ตเพลส
            $table->text('affiliate_url');

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index สำหรับ Query ลิงก์ของสินค้าแต่ละตัวอย่างรวดเร็ว
            $table->index(['product_id', 'is_active']);
        });

        // 3. Optional: ตารางเก็บ Click Log สำหรับนับยอด Conversion และแทร็กพฤติกรรมลูกค้า
        Schema::create('affiliate_click_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_link_id')->constrained('product_affiliate_links')->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('clicked_at')->useCurrent();
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
