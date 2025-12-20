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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Cashier ID
            $table->foreignId('customer_id')->nullable()->constrained();

            $table->decimal('total_amount', 10, 2);
            $table->decimal('received_amount', 10, 2);
            $table->decimal('change_amount', 10, 2);
            $table->string('payment_type')->default('cash'); // cash, transfer

            // Audit Trail
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();

            // Snapshot Data (สำคัญมาก: ห้าม Join เอาจาก Product มาแสดงย้อนหลัง)
            $table->string('product_name');
            $table->decimal('cost', 10, 2);
            $table->decimal('price', 10, 2);
            $table->integer('qty');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
