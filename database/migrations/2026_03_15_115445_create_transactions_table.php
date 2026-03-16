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
            $table->foreignId('shop_id')->constrained('shops')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->comment('พนักงานขาย');

            // ยอดเงิน
            $table->decimal('total_amount', 10, 2)->default(0)->comment('ยอดรวมสุทธิ');
            $table->decimal('receive_amount', 10, 2)->default(0)->comment('รับเงินมา');
            $table->decimal('change_amount', 10, 2)->default(0)->comment('เงินทอน');

            $table->string('payment_method', 50)->default('cash')->comment('วิธีชำระเงิน');
            $table->string('status', 20)->default('completed')->comment('สถานะบิล');

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
