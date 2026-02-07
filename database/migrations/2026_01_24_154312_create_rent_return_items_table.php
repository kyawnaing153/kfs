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
        Schema::create('rent_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rent_return_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rent_item_id')->constrained()->cascadeOnDelete();

            $table->integer('qty');
            $table->decimal('damage_fee', 12, 1)->default(0);

            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['rent_return_id', 'rent_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_return_items');
    }
};
