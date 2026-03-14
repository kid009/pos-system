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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // 💡 Architectural Highlight: ผูกกับ Shop เสมอ (Tenant Isolation)
            // cascadeOnDelete() หมายความว่าถ้าสาขาถูกลบ หมวดหมู่ของสาขานั้นจะหายไปด้วย
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();

            $table->string('name')->comment('ชื่อหมวดหมู่');
            $table->string('image_path')->nullable()->comment('รูปภาพหมวดหมู่');
            $table->boolean('is_active')->default(true)->comment('สถานะการแสดงผลหน้าร้าน');

            // ใช้ระบบ Audit Trail แบบเดียวกับ Shop
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
        Schema::dropIfExists('categories');
    }
};
