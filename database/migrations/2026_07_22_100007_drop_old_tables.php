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
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('sales_invoices');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate deliveries table
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained('sales_orders')->onDelete('cascade');
            $table->string('delivery_number')->unique();
            $table->string('status')->default('pending');
            $table->string('tracking_number')->nullable();
            $table->timestamps();
        });

        // Recreate sales_invoices table
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained('sales_orders')->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->bigInteger('amount');
            $table->string('status')->default('unpaid');
            $table->timestamps();
        });
    }
};
