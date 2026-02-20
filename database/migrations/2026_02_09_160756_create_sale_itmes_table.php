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
        Schema::create('sale_itmes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained();

            $table->integer('sale_qty');
            $table->string('unit', 20)->nullable();
            $table->decimal('unit_price', 12, 1);
            $table->decimal('discount', 12, 1)->nullable();
            $table->decimal('total', 12, 1)->nullable();

            $table->timestamps();

            $table->index(['sale_id', 'product_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_itmes');
    }
};
