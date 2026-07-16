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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('product_code');
            $table->string('name');
            $table->string('composition');
            $table->bigInteger('mrp');
            $table->bigInteger('sale_rate');
            $table->bigInteger('total_strip');
            $table->bigInteger('medicine_per_strip');
            $table->string('image_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
//    public function down(): void
//    {
//        Schema::dropIfExists('products');
//    }
};
