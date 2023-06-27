<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('couple_spendings', function (Blueprint $table) {
            $table->foreignId('couple_spending_place_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('couple_spendings', function (Blueprint $table) {
            $table->dropForeign(['couple_spending_place_id']);
        });
    }
};
