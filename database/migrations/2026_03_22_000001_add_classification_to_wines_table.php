<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wines', function (Blueprint $table) {
            $table->enum('classification', ['seco', 'demi_sec', 'suave', 'doce'])
                ->nullable()
                ->after('wine_type_id')
                ->comment('Classificação quanto ao teor de açúcar');
        });
    }

    public function down(): void
    {
        Schema::table('wines', function (Blueprint $table) {
            $table->dropColumn('classification');
        });
    }
};
