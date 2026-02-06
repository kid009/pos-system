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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('payment_date'); // วันที่ชำระเงิน

            // เชื่อมโยงกับตารางหมวดหมู่ (Foreign Key)
            $table->foreignId('expense_category_id')->constrained('expense_categories')->onDelete('cascade');

            $table->decimal('amount', 10, 2); // จำนวนเงิน
            $table->text('remark')->nullable(); // หมายเหตุ

            // Audit Trail
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
