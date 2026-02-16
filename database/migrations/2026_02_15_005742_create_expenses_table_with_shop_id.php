<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // สร้างตารางรายจ่าย (ถ้ายังไม่มี)
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade'); // ✅ ผูกกับร้าน
                $table->foreignId('user_id')->constrained('users'); // ใครเป็นคนคีย์
                $table->string('title'); // ชื่อรายการ (เช่น ค่าน้ำ)
                $table->decimal('amount', 10, 2);
                $table->date('expense_date');
                $table->foreignId('expense_category_id')->nullable()->constrained('expense_categories'); // หมวดหมู่
                $table->text('note')->nullable();
                $table->string('slip_image')->nullable(); // รูปใบเสร็จ
                $table->timestamps();
            });
        } else {
            // ถ้ามีแล้ว ให้เพิ่ม shop_id เข้าไป
            Schema::table('expenses', function (Blueprint $table) {
                if (!Schema::hasColumn('expenses', 'shop_id')) {
                    $table->foreignId('shop_id')->nullable()->after('id')->constrained('shops')->onDelete('cascade');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
