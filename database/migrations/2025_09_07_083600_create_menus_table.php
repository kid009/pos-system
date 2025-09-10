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
            $table->string('name'); // ชื่อเมนูที่จะแสดงผล เช่น "Dashboard"
            $table->string('icon')->nullable(); // ไอคอน (เช่น "home", "users")
            $table->string('route_name')->nullable(); // ชื่อ Route ที่จะลิงก์ไป
            $table->string('permission_name')->nullable(); // ชื่อ Permission ที่ต้องมีเพื่อเห็นเมนูนี้
            $table->foreignId('parent_id')->nullable()->constrained('menus')->onDelete('cascade'); // สำหรับเมนูย่อย
            $table->integer('sequence')->default(0); // ลำดับการแสดงผล
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
