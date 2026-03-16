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
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();

            $table->string('product_name')->comment('Snapshot ชื่อสินค้าตอนขาย');
            $table->decimal('cost', 10, 2)->default(0)->comment('Snapshot ต้นทุน');
            $table->decimal('price', 10, 2)->default(0)->comment('Snapshot ราคาขาย');

            $table->integer('qty')->comment('จำนวน');
            $table->decimal('subtotal', 10, 2)->default(0)->comment('รวมเป็นเงิน');

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
