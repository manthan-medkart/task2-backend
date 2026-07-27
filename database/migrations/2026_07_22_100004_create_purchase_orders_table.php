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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique(); // e.g., "PO-1721654321"
            $table->foreignId('purchase_indent_id')->constrained('purchase_indents')->onDelete('cascade');
            $table->string('status')->default('po_pending'); // po_pending, po_sent, po_procured
            $table->string('ecommerce_order_id')->nullable(); // to trace back to original customer order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
