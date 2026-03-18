<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('producer_id')->nullable()->nullOnDelete()->constrained();
            $table->foreignId('country_id')->nullable()->nullOnDelete()->constrained();
            $table->foreignId('region_id')->nullable()->nullOnDelete()->constrained();
            $table->foreignId('wine_type_id')->nullable()->nullOnDelete()->constrained();
            $table->smallInteger('vintage')->nullable()->comment('Safra/ano');
            $table->longText('description')->nullable()->comment('Notas de degustação');
            $table->decimal('alcohol_content', 4, 2)->nullable()->comment('Teor alcoólico %');
            $table->tinyInteger('serving_temp_min')->nullable()->comment('Temperatura mínima de serviço °C');
            $table->tinyInteger('serving_temp_max')->nullable()->comment('Temperatura máxima de serviço °C');
            $table->tinyInteger('rating')->nullable()->comment('Pontuação 1-100');
            $table->decimal('cost_price', 10, 2)->nullable()->comment('Preço de custo');
            $table->decimal('sale_price', 10, 2)->nullable()->comment('Preço de venda');
            $table->integer('stock_quantity')->default(0)->comment('Estoque');
            $table->enum('stock_unit', ['bottle', 'magnum', 'half_bottle'])->default('bottle');
            $table->string('barcode', 100)->nullable()->unique()->comment('Código de barras EAN/UPC');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wines');
    }
};
