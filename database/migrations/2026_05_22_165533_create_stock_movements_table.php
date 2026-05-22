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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Foreign Keys with Strict Constraints
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');

            $table->string('type'); // in, out, adjustment
            $table->integer('qty'); // Supports positive/negative values
            $table->decimal('unit_cost', 15, 4)->default(0.0000);
            $table->string('reference')->nullable();
            $table->timestamps(); // created_at represents transactional timestamp

            // Composite Index for high-performance stock card queries
            $table->index(['product_id', 'warehouse_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
