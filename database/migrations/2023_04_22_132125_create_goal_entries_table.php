<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('goal_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('goal_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->decimal('amount', 8, 2);
            $table->date('date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goal_entries');
    }
};
