<?php

use App\Http\Controllers\Api\V1\BeverageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.api_token')->group(function () {
    Route::get('bebida/aleatorio', [BeverageController::class, 'random']);
    Route::get('bebida/{barcode}', [BeverageController::class, 'show']);
});
