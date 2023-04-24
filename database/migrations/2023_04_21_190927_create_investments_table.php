<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('description');
            $table->string('owner');
            $table->date('start_date');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
