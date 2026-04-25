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
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('ชื่อรายการรายจ่าย เช่น ค่าไฟฟ้า, ค่าน้ำ');
            $table->string('group_name')->comment('หมวดหมู่หลัก เช่น ค่าใช้จ่ายคงที่, ค่าแรง');
            $table->boolean('is_active')->default(true)->comment('สถานะการใช้งาน');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_categories');
    }
};
