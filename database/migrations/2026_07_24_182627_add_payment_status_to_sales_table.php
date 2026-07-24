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
        Schema::table('sales', function (Blueprint $table) {
            $table->string('payment_status', 20)->default('paid')->after('payment_method');
            $table->string('midtrans_order_id', 100)->nullable()->unique()->after('payment_status');
            $table->text('snap_token')->nullable()->after('midtrans_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'midtrans_order_id', 'snap_token']);
        });
    }
};
