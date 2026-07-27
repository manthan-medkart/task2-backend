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
        Schema::create('purchase_indents', function (Blueprint $table) {
            $table->id();
            $table->string('indent_number')->unique(); // e.g., "PI-1721654321"
            $table->string('status')->default('pending'); // pending, po_created
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_indents');
    }
};
