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
            $table->bigInteger('product_code')->unique();
            $table->string('name');
            $table->string('composition');
            $table->bigInteger('mrp');
            $table->bigInteger('sales_rate');
            $table->bigInteger('total_strip');
            $table->bigInteger('medicine_per_strip');
            $table->string('image_url');
            $table->timestamps();
        });

        // Add auto-incrementing sequence for PostgreSQL
        if (config('database.default') === 'pgsql') {
            \Illuminate\Support\Facades\DB::statement('CREATE SEQUENCE products_product_code_seq START WITH 100001');
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE products ALTER COLUMN product_code SET DEFAULT nextval('products_product_code_seq')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        if (config('database.default') === 'pgsql') {
            \Illuminate\Support\Facades\DB::statement('DROP SEQUENCE IF EXISTS products_product_code_seq');
        }
    }
};
