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
            $table->string('product_name')->index();
            $table->enum('product_type', ['sale', 'rent', 'both'])->index();
            $table->text('description')->nullable();
            $table->string('thumb')->nullable();
            $table->boolean('status')->default(true)->index();   // frontend visibility
            $table->boolean('is_feature')->default(false)->index(); // admin rent page
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
