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
        Schema::create('inventory_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();

            // ประเภทธุรกรรม: 'SALE' (ขายออก), 'RESTOCK' (รับเข้า), 'ADJUST' (ปรับสต็อกมือ), 'RETURN' (คืนสินค้า)
            $table->string('transaction_type');

            $table->integer('quantity'); // จำนวนที่เปลี่ยน (เช่น -1, +20)
            $table->integer('balance_after'); // สต็อกคงเหลือในโกดังทันทีหลังจากจบ Event นี้

            // ระบบบันทึกเอกสารอ้างอิงแบบยืดหยุ่น (Polymorphic Relation)
            // เช่น ลิงก์ไปที่ตาราง Orders, ตารางใบรับของเข้า หรือประวัติผู้ใช้งานที่กดแก้สต็อก
            $table->nullableMorphs('referenceable');

            $table->timestamps();

            $table->index(['product_id', 'created_at']);
        });

        // 2. ตารางแคชยอดสรุปรายเดือน เพื่อความเร็วในการรันรายงานปิดรอบบัญชีหน้าร้าน
        Schema::create('inventory_monthly_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('year_month', 7); // เก็บฟอร์แมต "2026-07"

            $table->integer('opening_balance')->default(0);   // ยอดสต็อกยกมา (ยกมาจากยอดปิดเดือนที่แล้ว)
            $table->integer('total_received')->default(0);    // ยอดเติมของเข้าทั้งหมดในเดือนนี้
            $table->integer('total_sold')->default(0);        // ยอดขายออกทั้งหมดในเดือนนี้
            $table->integer('closing_balance')->default(0);   // ยอดสต็อกยกไป ( Opening + Received - Sold )

            $table->timestamps();

            // คุมไม่ให้ข้อมูลซ้ำซ้อนในเดือนเดียวกัน และเพิ่มประสิทธิภาพความเร็ว
            $table->unique(['product_id', 'year_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
