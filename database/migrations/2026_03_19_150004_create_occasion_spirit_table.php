<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('occasion_spirit', function (Blueprint $table) {
            $table->foreignId('occasion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('spirit_id')->constrained()->cascadeOnDelete();
            $table->primary(['occasion_id', 'spirit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('occasion_spirit');
    }
};
