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
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('total_amount'); // ค่าส่ง
            $table->decimal('discount_amount', 10, 2)->default(0)->after('delivery_fee'); // ส่วนลด
            $table->string('note')->nullable()->after('status'); // หมายเหตุ (เช่น ฝากไว้ก่อน)
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            // สถานะถัง: empty (ถังเปล่า), refill (หมุนเวียน), new (ถังใหม่), deposit (ฝากเติม)
            $table->string('gas_status')->nullable()->after('product_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
