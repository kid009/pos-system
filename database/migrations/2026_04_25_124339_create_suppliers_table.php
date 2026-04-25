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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('ชื่อบริษัท/ร้านค้าซัพพลายเออร์');
            $table->string('contact_name')->nullable()->comment('ชื่อผู้ติดต่อประสานงาน');
            $table->string('phone', 20)->nullable()->comment('เบอร์โทรศัพท์');
            $table->text('address')->nullable()->comment('ที่อยู่บริษัท');
            $table->string('tax_id', 13)->nullable()->comment('เลขผู้เสียภาษี 13 หลัก');
            $table->boolean('is_active')->default(true)->comment('สถานะ 1=ใช้งาน, 0=ระงับ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
