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
        Schema::create('sales_indents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained('sales_orders')->onDelete('cascade');
            $table->bigInteger('product_code');
            $table->integer('required_quantity'); // quantity the customer ordered
            $table->integer('order_quantity');    // min quantity to order from vendor (editable)
            $table->string('status')->default('pending'); // pending, purchase_indent_created
            $table->timestamps();

            $table->foreign('product_code')->references('product_code')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_indents');
    }
};
