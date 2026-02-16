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
        // 1. ตาราง Roles (เก็บชื่อบทบาท)
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // ชื่อบทบาท (ภาษาอังกฤษ) เช่น admin, user
            $table->string('label')->nullable(); // ชื่อที่แสดง (ภาษาไทย) เช่น ผู้ดูแลระบบ
            $table->timestamps();
        });

        // 2. ตาราง Role_User (ตารางเชื่อม Many-to-Many)
        // ตามมาตรฐาน Laravel จะใช้ชื่อ role_user (เรียงตามตัวอักษร)
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // ป้องกันการจับคู่ซ้ำ
            $table->unique(['user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
};
