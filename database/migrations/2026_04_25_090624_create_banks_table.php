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
        Schema::create('banks', function (Blueprint $table) {
            $table->id(); // สร้างฟิลด์ id (Unsigned BigInt, PK, Auto Increment)

            $table->string('name')->comment('ชื่อธนาคาร (เช่น กสิกรไทย)'); // Varchar(255), Not Null
            $table->string('code', 50)->comment('รหัสย่อธนาคาร (เช่น KBANK)'); // Varchar(50), Not Null
            $table->string('account_name')->nullable()->comment('ชื่อบัญชี'); // Varchar(255), Nullable
            $table->string('account_no', 50)->nullable()->comment('เลขที่บัญชีธนาคาร'); // Varchar(50), Nullable

            $table->boolean('is_active')->default(true)->comment('สถานะ 1=ใช้งาน, 0=ระงับ'); // Boolean, Not Null, Default 1

            $table->timestamps(); // สร้างฟิลด์ created_at และ updated_at ให้อัตโนมัติ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
