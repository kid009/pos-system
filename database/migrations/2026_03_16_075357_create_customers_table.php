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
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->restrictOnDelete();

            $table->string('name')->comment('ชื่อ-นามสกุล หรือ ชื่อบริษัท');
            $table->string('phone', 20)->nullable()->comment('เบอร์โทรศัพท์');
            $table->text('address')->nullable()->comment('ที่อยู่');
            $table->string('tax_id', 20)->nullable()->comment('เลขประจำตัวผู้เสียภาษี');

            $table->integer('points')->default(0)->comment('แต้มสะสม');
            $table->boolean('is_active')->default(true)->comment('สถานะใช้งาน');

            $table->timestamps();
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
