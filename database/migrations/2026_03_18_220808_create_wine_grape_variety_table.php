<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wine_grape_variety', function (Blueprint $table) {
            $table->foreignId('wine_id')->constrained()->cascadeOnDelete();
            $table->foreignId('grape_variety_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('percentage')->nullable()->comment('Percentual no blend');
            $table->primary(['wine_id', 'grape_variety_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wine_grape_variety');
    }
};
