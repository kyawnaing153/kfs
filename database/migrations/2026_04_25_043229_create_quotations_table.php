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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_code')->unique();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['rent', 'purchase'])->default('rent');
            $table->date('quotation_date')->default(now());
            $table->date('rent_date')->default(now());
            $table->integer('rent_duration')->nullable();
            $table->boolean('transport_required')->default(false);
            $table->decimal('sub_total', 12, 1)->default(0);
            $table->decimal('deposit', 12, 1)->default(0);
            $table->decimal('transport', 12, 1)->default(0);
            $table->decimal('discount', 12, 1)->default(0);
            $table->decimal('total', 12, 1)->default(0);
            $table->enum('status', ['submitted','approved','rejected','converted'])->default('submitted')->index();
            $table->text('transport_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
