<?php

use App\Http\Controllers\KioskController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [KioskController::class, 'home']);
Route::get('/vinho/aleatorio', [KioskController::class, 'random'])->name('kiosk.random');
Route::get('/vinho/{barcode}', [KioskController::class, 'show'])->name('kiosk.wine');
Route::get('/destilado/{barcode}', [KioskController::class, 'showSpirit'])->name('kiosk.spirit');
Route::post('/reportar-bebida', [KioskController::class, 'report'])->name('kiosk.report');
Route::get('/catalogo', [HomeController::class, 'catalogo'])->name('catalogo');
