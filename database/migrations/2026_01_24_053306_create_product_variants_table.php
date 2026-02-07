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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('size', 50)->nullable();
            $table->string('unit', 20)->nullable();
            $table->integer('qty')->default(0)->index();
            $table->decimal('purchase_price', 12, 1)->default(0);
            $table->string('sku', 100)->unique();
            $table->timestamps();

            $table->index(['product_id', 'size']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
