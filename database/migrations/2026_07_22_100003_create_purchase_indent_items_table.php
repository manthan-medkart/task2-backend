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
        Schema::create('purchase_indent_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_indent_id')->constrained('purchase_indents')->onDelete('cascade');
            $table->foreignId('sales_indent_id')->nullable()->constrained('sales_indents')->onDelete('set null');
            $table->bigInteger('product_code');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('product_code')->references('product_code')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_indent_items');
    }
};
