<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();

            // Editable colors
            $table->string('color_primary');
            $table->string('color_secondary');
            $table->string('color_background');
            $table->string('color_text');

            // Immutable defaults (set only by seeder, used by "Restaurar Padrão")
            $table->string('default_color_primary');
            $table->string('default_color_secondary');
            $table->string('default_color_background');
            $table->string('default_color_text');

            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Remove color columns from settings (now handled by active theme)
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'color_primary',
                'color_secondary',
                'color_background',
                'color_text',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themes');

        Schema::table('settings', function (Blueprint $table) {
            $table->string('color_primary')->default('#c8a96e');
            $table->string('color_secondary')->default('#e2c98a');
            $table->string('color_background')->default('#0d0d0f');
            $table->string('color_text')->default('#f5f0eb');
        });
    }
};
