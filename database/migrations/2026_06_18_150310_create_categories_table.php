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
            $table->string('name');
            // slug สำหรับทำ SEO Friendly URL (เช่น gas-stoves)
            $table->string('slug')->unique();

            // ข้อมูลอธิบายหมวดหมู่สั้น ๆ ช่วยให้หน้าร้านแสดงผลและทำ SEO ได้ดีขึ้น
            $table->text('description')->nullable();

            // รองรับลำดับการแสดงผลบนหน้าเว็บ (เช่น อยากเอาเตาแก๊สขึ้นก่อนอะไหล่)
            $table->integer('sort_order')->default(0);

            // คอลัมน์ควบคุมการเปิด-ปิดหมวดหมู่จากหน้าร้าน (เช่น สินค้าหมดชั่วคราว หรือยกเลิกขาย)
            $table->boolean('is_active')->default(true);

            // (Optional) รองรับ Sub-category ในอนาคต เช่น เตาแก๊ส > เตาแก๊สบ้าน / เตาแก๊สหัวเร่ง
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();

            $table->timestamps();

            // Index สำหรับการเรียงลำดับและการดึงหมวดหมู่ที่เปิดใช้งานอยู่ไปแสดงผลอย่างรวดเร็ว
            $table->index(['is_active', 'sort_order']);
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
