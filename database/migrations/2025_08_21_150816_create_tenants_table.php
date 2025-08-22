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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id(); // Column: id 
            $table->string('name'); // Column: name 
            $table->string('domain')->unique()->nullable(); // Column: domain 
            $table->string('status')->default('active'); // Column: status 
            $table->string('logo_path')->nullable(); // Column: logo_path 
            $table->text('receipt_header_text')->nullable(); // Column: receipt_header_text 
            $table->text('receipt_footer_text')->nullable(); // Column: receipt_footer_text 
            $table->timestamps(); // Columns: created_at, updated_at 
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
