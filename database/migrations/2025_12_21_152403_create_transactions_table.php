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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique(); // เลขที่ใบเสร็จ (เช่น INV-20231001-001)
            $table->foreignId('user_id')->constrained(); // พนักงานขาย
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete(); // ลูกค้า (ถ้ามี)
            $table->decimal('total_amount', 10, 2); // ยอดรวมทั้งบิล
            $table->decimal('received_amount', 10, 2); // รับเงินมา
            $table->decimal('change_amount', 10, 2); // เงินทอน
            $table->enum('payment_method', ['cash', 'unpaid', 'transfer', 'half_half'])->default('cash');
            $table->string('status')->default('completed'); // สถานะ (completed, void)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
