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
        Schema::table('stock_logs', function (Blueprint $table) {
            $table->integer('stock_before')->default(0)->after('quantity_change');
            $table->integer('stock_after')->default(0)->after('stock_before');
            $table->string('source')->nullable()->after('description');
            // source values: order_placed, order_cancelled, po_procured, manual
            $table->string('updated_by')->nullable()->after('source');
            // updated_by values: ecommerce, wms_admin, system
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_logs', function (Blueprint $table) {
            $table->dropColumn(['stock_before', 'stock_after', 'source', 'updated_by']);
        });
    }
};
