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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained(); // เชื่อมสินค้า
            $table->string('product_name'); // บันทึกชื่อตอนขาย (เผื่ออนาคตเปลี่ยนชื่อสินค้า ประวัติจะไม่เพี้ยน)
            $table->decimal('price', 10, 2); // ราคาต่อชิ้นตอนขาย
            $table->integer('quantity'); // จำนวนที่ซื้อ
            $table->decimal('total_price', 10, 2); // price * quantity
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
