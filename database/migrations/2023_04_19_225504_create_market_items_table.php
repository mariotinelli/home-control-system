<?php

use App\Enums\TypeOfWeightEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('market_items', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->foreignId('market_item_category_id')->constrained();
            $table->enum('type_weight', TypeOfWeightEnum::getValues());
            $table->decimal('weight', 10, 2);
            $table->decimal('price', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_items');
    }
};
