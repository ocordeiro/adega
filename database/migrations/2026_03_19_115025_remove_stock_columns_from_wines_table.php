<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wines', function (Blueprint $table) {
            $table->dropColumn(['cost_price', 'sale_price', 'stock_quantity', 'stock_unit']);
        });
    }

    public function down(): void
    {
        Schema::table('wines', function (Blueprint $table) {
            $table->decimal('cost_price', 10, 2)->nullable()->after('rating');
            $table->decimal('sale_price', 10, 2)->nullable()->after('cost_price');
            $table->integer('stock_quantity')->default(0)->after('sale_price');
            $table->enum('stock_unit', ['bottle', 'magnum', 'half_bottle'])->default('bottle')->after('stock_quantity');
        });
    }
};
