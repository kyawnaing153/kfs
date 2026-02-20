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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_code')->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            
            $table->date('sale_date');

            $table->decimal('sub_total', 12, 1);
            $table->decimal('discount', 12, 1)->default(0);
            $table->decimal('transport', 12, 1)->default(0);
            $table->decimal('total', 12, 1)->default(0);

            $table->decimal('total_paid', 12, 1)->default(0);
            $table->decimal('total_due', 12, 1);

            $table->string('payment_type')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending')->index();
            $table->timestamps();

            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
