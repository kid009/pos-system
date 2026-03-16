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
        Schema::table('customers', function (Blueprint $table) {
            // เติมคอลัมน์ created_by และ updated_by (อนุญาตให้เป็น null ได้เผื่อไว้)
            $table->unsignedBigInteger('created_by')->nullable()->after('is_active')->comment('ผู้สร้างข้อมูล');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by')->comment('ผู้แก้ไขล่าสุด');

            // 💡 ถ้าในระบบคุณมี deleted_by ด้วย ก็เปิดคอมเมนต์บรรทัดล่างนี้ครับ
            // $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
};
