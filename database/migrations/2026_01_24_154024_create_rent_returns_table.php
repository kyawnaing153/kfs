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
        Schema::create('rent_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rent_id')->constrained();

            $table->decimal('refund_amount', 12, 1)->default(0);
            $table->decimal('collect_amount', 12, 1)->default(0);

            $table->date('return_date');
            $table->string('return_image')->nullable();

            $table->enum('status', ['partial', 'completed'])
                ->default('partial')
                ->index();

            $table->text('note')->nullable();
            $table->timestamps();

            $table->index('rent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_returns');
    }
};
