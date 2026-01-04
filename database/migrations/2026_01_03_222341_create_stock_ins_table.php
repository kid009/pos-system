<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. ตารางหัวบิลรับเข้า (StockIns)
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id();
            $table->date('import_date'); // วันที่นำเข้า
            $table->string('supplier_name'); // ชื่อร้านค้า/ซัพพลายเออร์
            $table->enum('payment_type', ['cash', 'credit', 'transfer'])->default('cash')->comment('cash=เงินสด, credit=ค้างชำระ/เครดิต, transfer=โอนธนาคาร');
            $table->decimal('total_amount', 10, 2)->default(0); // ยอดรวมทั้งบิล
            $table->foreignId('user_id')->constrained(); // ใครเป็นคนคีย์ข้อมูล
            $table->timestamps();
        });

        // 2. ตารางรายละเอียดสินค้า (StockInDetails)
        Schema::create('stock_in_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_in_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();

            $table->integer('qty'); // จำนวนที่สั่ง
            $table->decimal('unit_cost', 10, 2); // ราคาต้นทุนต่อหน่วย (ณ วันที่ซื้อ)
            $table->decimal('total_cost', 10, 2); // ราคารวม (qty * unit_cost)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_in_details');
        Schema::dropIfExists('stock_ins');
    }
};
