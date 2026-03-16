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
        Schema::table('users', function (Blueprint $table) {
            // 💡 เพิ่มคอลัมน์ role และ is_active ต่อท้ายคอลัมน์ password
            $table->string('role', 50)->default('staff')->after('password')->comment('สิทธิ์การใช้งาน: admin, owner, staff');
            $table->boolean('is_active')->default(true)->after('role')->comment('สถานะการเข้าใช้งานระบบ');

            // (Optional) ถ้าคุณมีแผนจะให้ Staff ผูกกับ Shop ใด Shop หนึ่งโดยเฉพาะ
            // $table->foreignId('shop_id')->nullable()->after('is_active')->constrained('shops')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ลบทิ้งเมื่อสั่ง rollback
            $table->dropColumn(['role', 'is_active']);
            // $table->dropForeign(['shop_id']);
            // $table->dropColumn('shop_id');
        });
    }
};
