<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('market_stock_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('market_stock_id')->constrained();
            $table->foreignId('market_id')->constrained();
            $table->decimal('price', 10, 2);
            $table->integer('quantity');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_stock_entries');
    }
};
