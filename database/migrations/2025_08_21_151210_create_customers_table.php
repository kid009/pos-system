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
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // Column: id [cite: 18]
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade'); // Column: tenant_id [cite: 18, 20]
            $table->string('name'); // Column: name [cite: 18]
            $table->string('phone')->nullable(); // Column: phone [cite: 18]
            $table->text('address')->nullable(); // Column: address [cite: 18]
            $table->string('tax_id')->nullable(); // Column: tax_id [cite: 18]
            $table->decimal('latitude', 10, 7)->nullable(); // Column: latitude [cite: 18]
            $table->decimal('longitude', 10, 7)->nullable(); // Column: longitude [cite: 18]
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // Column: created_by [cite: 18, 21]
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // Column: updated_by [cite: 18, 22]
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
