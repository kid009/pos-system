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
            // เพิ่มคอลัมน์ invoice_no ไว้หลัง id และกำหนดให้ไม่ซ้ำกัน (unique)
            $table->string('invoice_no', 20)->unique()->after('id')->comment('เลขที่ใบเสร็จ');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('invoice_no');
        });
    }
};
