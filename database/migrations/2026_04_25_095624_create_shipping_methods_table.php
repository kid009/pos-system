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
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id(); // รหัสอ้างอิง (PK)
            $table->string('name')->comment('ชื่อบริษัทขนส่ง (เช่น Kerry, Flash)');
            $table->boolean('is_active')->default(true)->comment('สถานะ 1=ใช้งาน, 0=ระงับ');
            $table->timestamps(); // created_at และ updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
