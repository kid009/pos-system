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
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();

            $table->decimal('price', 8, 2); // ราคาขายหน้าเว็บ / หน้าร้าน POS
            $table->decimal('cost', 8, 2)->default(0); // ราคาต้นทุน ณ ล็อตนั้น (ใช้คิดกำไรสุทธิย้อนหลัง)

            $table->dateTime('started_at'); // วัน-เวลาที่เริ่มใช้ราคานี้
            $table->dateTime('ended_at')->nullable(); // วัน-เวลาที่สิ้นสุดการใช้ราคานี้ (ถ้า NULL แปลว่าเป็นราคาปัจจุบัน)
            $table->timestamps();

            // Index สำหรับขุดหาราคา ณ ช่วงเวลาใดเวลาหนึ่ง (Temporal Query Optimization)
            $table->index(['product_id', 'started_at', 'ended_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
