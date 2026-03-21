<?php

use App\Http\Controllers\Api\V1\AdController;
use App\Http\Controllers\Api\V1\BeverageController;
use App\Http\Controllers\Api\V1\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.api_token')->group(function () {
    Route::get('bebida/aleatorio', [BeverageController::class, 'random']);
    Route::get('bebida/{barcode}', [BeverageController::class, 'show']);

    Route::post('bebida/reportar', [BeverageController::class, 'report']);

    Route::get('anuncios', [AdController::class, 'index']);
    Route::get('configuracoes', [SettingController::class, 'index']);
});
