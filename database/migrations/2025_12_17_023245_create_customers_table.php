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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // ข้อมูลจากไฟล์ CSV
            $table->string('code')->nullable();              // รหัสลูกค้า
            $table->string('name');                          // ชื่อลูกค้า
            $table->string('phone')->nullable();             // เบอร์
            $table->decimal('latitude', 10, 7)->nullable();  // ละติจูด (ทศนิยม 7 ตำแหน่งเพียงพอสำหรับ GPS)
            $table->decimal('longitude', 10, 7)->nullable(); // ลองติจูด
            $table->string('line_id')->nullable();           // LINE
            $table->text('notes')->nullable();               // หมายเหตุ

            // ข้อมูลเพิ่มเติมสำหรับระบบ
            $table->text('address')->nullable();             // ที่อยู่ (เผื่อไว้)
            $table->enum('type', ['general', 'member', 'wholesale'])->default('general'); // ประเภทลูกค้า

            // Tracking Fields (ใครสร้าง/ใครแก้)
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // Foreign Keys (Optional: เชื่อมกับตาราง Users)
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
