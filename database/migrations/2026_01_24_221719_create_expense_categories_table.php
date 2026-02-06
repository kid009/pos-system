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
            $table->string('name');  // เก็บ "รายการ" เช่น ค่าเช่า, ค่าน้ำ
            $table->string('group')->nullable(); // เก็บ "หมวดหมู่" เช่น ค่าใช้จ่ายคงที่, ค่าแรง
            $table->string('color')->default('secondary'); // เผื่อไว้ใส่สีป้าย
            $table->boolean('is_active')->default(true);   // เผื่อปิดการใช้งาน
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
