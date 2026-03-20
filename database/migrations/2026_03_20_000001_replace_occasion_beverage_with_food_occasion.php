<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('wine_occasion');
        Schema::dropIfExists('occasion_spirit');

        Schema::create('food_occasion', function (Blueprint $table) {
            $table->foreignId('food_id')->constrained('foods')->cascadeOnDelete();
            $table->foreignId('occasion_id')->constrained()->cascadeOnDelete();
            $table->primary(['food_id', 'occasion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_occasion');

        Schema::create('wine_occasion', function (Blueprint $table) {
            $table->foreignId('wine_id')->constrained()->cascadeOnDelete();
            $table->foreignId('occasion_id')->constrained()->cascadeOnDelete();
            $table->primary(['wine_id', 'occasion_id']);
        });

        Schema::create('occasion_spirit', function (Blueprint $table) {
            $table->foreignId('occasion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('spirit_id')->constrained()->cascadeOnDelete();
            $table->primary(['occasion_id', 'spirit_id']);
        });
    }
};
