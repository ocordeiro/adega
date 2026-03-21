<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('color_primary')->default('#c8a96e');
            $table->string('color_secondary')->default('#e2c98a');
            $table->string('color_background')->default('#0d0d0f');
            $table->string('color_text')->default('#f5f0eb');
            $table->decimal('element_scale', 3, 2)->default(1.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
