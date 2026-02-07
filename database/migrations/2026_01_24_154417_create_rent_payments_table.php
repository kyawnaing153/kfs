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
        Schema::create('rent_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rent_id')->constrained()->cascadeOnDelete();

            $table->decimal('amount', 12, 1);
            $table->string('payment_method')->nullable()->index();
            $table->date('payment_date')->index();

            $table->enum('payment_for', [
                'monthly',
                'final',
                'penalty',
            ])->default('monthly')->index();

            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();

            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['rent_id', 'payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_payments');
    }
};
