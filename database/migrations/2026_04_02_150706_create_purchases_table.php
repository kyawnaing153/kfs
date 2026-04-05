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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_code')->unique();
            $table->foreignId('supplier_id')->constrained()->onDelete('restrict');
            $table->date('purchase_date');
            $table->decimal('sub_total', 12, 1)->default(0);
            $table->decimal('transport', 12, 1)->nullable();
            $table->decimal('discount', 12, 1)->default(0);
            $table->decimal('tax', 12, 1)->default(0);
            $table->decimal('total_amount', 12, 1)->default(0);
            $table->tinyInteger('payment_status')->default(0)->comment('0=unpaid, 1=paid');
            $table->tinyInteger('status')->default(0)->comment('0=pending, 1=delivered');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();

            $table->index('purchase_code');
            $table->index('purchase_date');
            $table->index('payment_status');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
