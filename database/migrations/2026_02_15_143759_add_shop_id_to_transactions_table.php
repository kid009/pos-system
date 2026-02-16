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
        Schema::table('transactions', function (Blueprint $table) {
            // เพิ่ม shop_id และบังคับว่าห้ามว่าง (หรือ nullable ไปก่อนแล้วค่อยมาแก้ข้อมูล)
            $table->foreignId('shop_id')->nullable()->after('id')->constrained('shops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
