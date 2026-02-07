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
        Schema::table('rent_returns', function (Blueprint $table) {
            $table->integer('total_days')->after('return_date');
            $table->decimal('transport', 12, 1)->default(0)->after('total_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rent_returns', function (Blueprint $table) {
            $table->dropColumn(['total_days', 'transport']);
        });
    }
};
