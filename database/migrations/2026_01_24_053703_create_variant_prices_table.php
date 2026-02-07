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
        Schema::create('variant_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->cascadeOnDelete();

            $table->enum('price_type', ['sale', 'rent'])->index();
            $table->unsignedInteger('duration_days')->nullable(); // NULL for sale
            $table->decimal('price', 12, 1);
            $table->timestamps();

            $table->unique(
                ['product_variant_id', 'price_type', 'duration_days'],
                'variant_price_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_prices');
    }
};
