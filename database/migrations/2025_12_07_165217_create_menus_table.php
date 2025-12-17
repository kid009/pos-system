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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // ชื่อเมนูที่แสดง (เช่น "POS System")
            $table->string('route')->nullable(); // Route Name (เช่น "pos.index")
            $table->text('icon')->nullable();  // SVG String หรือ Class Icon
            $table->string('permission_name')->nullable(); // *Key Point: ชื่อ Permission ที่ต้องมี
            $table->integer('order')->default(0); // ลำดับการเรียง
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('parent_id')->nullable(); // รองรับ Sub-menu (Optional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
