<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drink_recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drink_recipe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('spirit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drink_recipe_ingredients');
    }
};
