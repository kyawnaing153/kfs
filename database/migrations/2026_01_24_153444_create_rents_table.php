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
        Schema::create('rents', function (Blueprint $table) {
            $table->id();
            $table->string('rent_code')->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();

            $table->date('rent_date');

            $table->decimal('sub_total', 12, 1);
            $table->decimal('discount', 12, 1)->default(0);
            $table->decimal('deposit', 12, 1)->default(0);
            $table->decimal('transport', 12, 1)->default(0);
            $table->decimal('total', 12, 1);

            $table->decimal('total_paid', 12, 1)->default(0);
            $table->decimal('total_due', 12, 1);

            $table->string('payment_type');
            $table->string('document')->nullable();

            $table->enum('status', ['pending', 'ongoing', 'completed'])
                ->default('pending')
                ->index();

            $table->text('note')->nullable();
            $table->timestamps();

            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rents');
    }
};
