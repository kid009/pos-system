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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no', 50)->unique()->comment('เลขที่เอกสารรับเข้า (IN-xxxx)');

            // ผูกกับตาราง suppliers (ถ้าลบซัพพลายเออร์ จะไม่ยอมให้ลบถ้ามีบิลผูกอยู่ - restrictOnDelete)
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();

            $table->date('doc_date')->comment('วันที่ตามเอกสารใบเสร็จ');

            // โครงสร้างตัวเลขทางบัญชี
            $table->decimal('total_amount', 10, 2)->default(0)->comment('ยอดรวมราคาสินค้า');
            $table->decimal('shipping_cost', 10, 2)->default(0)->comment('ค่าขนส่งที่นำมาบวกเป็นต้นทุน');
            $table->decimal('discount', 10, 2)->default(0)->comment('ส่วนลดท้ายบิล');
            $table->decimal('net_amount', 10, 2)->default(0)->comment('ยอดจ่ายสุทธิ');

            // สถานะบิล (เผื่อใช้ยกเลิกบิลในอนาคต)
            $table->enum('status', ['completed', 'cancelled'])->default('completed')->comment('สถานะบิล');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
