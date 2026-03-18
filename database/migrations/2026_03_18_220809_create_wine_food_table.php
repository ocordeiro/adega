<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wine_food', function (Blueprint $table) {
            $table->foreignId('wine_id')->constrained('wines')->cascadeOnDelete();
            $table->foreignId('food_id')->constrained('foods')->cascadeOnDelete();
            $table->text('notes')->nullable()->comment('Notas de harmonização');
            $table->primary(['wine_id', 'food_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wine_food');
    }
};
