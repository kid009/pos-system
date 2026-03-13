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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();

            // ข้อมูลพื้นฐาน
            $table->string('name')->comment('ชื่อร้านค้า/บริษัท');
            $table->text('address')->nullable()->comment('ที่อยู่ร้านค้า');

            // 💡 ปรับความยาวเพิ่มขึ้น ป้องกัน Data Truncation Error
            $table->string('phone', 30)->nullable()->comment('เบอร์ติดต่อ');

            // ข้อมูลทางภาษีและนิติบุคคล
            $table->string('tax_id', 20)->nullable()->comment('เลขประจำตัวผู้เสียภาษี');
            $table->string('branch_code', 50)->nullable()->comment('รหัสสาขา เช่น 00000 (สำนักงานใหญ่)');

            // รูปภาพ
            $table->string('logo_path')->nullable()->comment('พาร์ทเก็บรูปโลโก้');

            // JSON Settings (ยืดหยุ่นต่อการเปลี่ยนแปลง)
            $table->json('settings')->nullable()->comment('ตั้งค่าอื่นๆ ของร้านค้า (JSON)');

            // สถานะ
            $table->boolean('is_active')->default(true)->comment('สถานะการเปิดใช้งาน');

            // 💡 Architectural Fix: เปลี่ยนจาก String เป็น Foreign Key (Nullable เผื่อกรณี Seeder หรือ System create)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('ผู้สร้างรายการ');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->comment('ผู้แก้ไขล่าสุด');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
