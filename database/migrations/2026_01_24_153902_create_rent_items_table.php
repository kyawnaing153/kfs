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
        Schema::create('rent_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rent_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained();

            $table->integer('rent_qty');
            $table->integer('returned_qty')->default(0);

            $table->string('unit', 20)->nullable();
            $table->decimal('unit_price', 12, 1);
            $table->decimal('total', 12, 1);

            $table->timestamps();

            $table->index(['rent_id', 'product_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_items');
    }
};
