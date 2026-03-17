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
        Schema::table('shops', function (Blueprint $table) {
            $table->boolean('show_discount_on_receipt')->default(true)->after('settings');
            $table->boolean('show_shipping_on_receipt')->default(true)->after('show_discount_on_receipt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['show_discount_on_receipt', 'show_shipping_on_receipt']);
        });
    }
};
