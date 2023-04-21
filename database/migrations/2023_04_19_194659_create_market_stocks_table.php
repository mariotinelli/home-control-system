<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('market_stocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('market_item_id')->constrained();
            $table->integer('quantity');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_stocks');
    }
};
