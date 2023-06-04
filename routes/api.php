<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\SalesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'beta'], function () {
    // Kendaraan Routes
    Route::get('/kendaraan', [KendaraanController::class, 'index']);
    Route::post('/kendaraan', [KendaraanController::class, 'store']);
    Route::put('/kendaraan/{id}', [KendaraanController::class, 'update']);
    Route::get('/kendaraan/{id}', [KendaraanController::class, 'show']);
    Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy']);

    // Stock Routes
    Route::get('/stock/kendaraan/all', [KendaraanController::class, 'stockAll']);
    Route::get('/stock/kendaraan/{id}', [KendaraanController::class, 'stockKendaraan']);

    // Motor Routes
    Route::get('/motor', [MotorController::class, 'motorIndex']);
    Route::post('/motor', [MotorController::class, 'motorStore']);
    Route::put('/motor/{id}', [MotorController::class, 'motorUpdate']);
    Route::get('/motor/{id}', [MotorController::class, 'motorShow']);
    Route::delete('/motor/{id}', [MotorController::class, 'motorDestroy']);

    // Mobil Routes
    Route::get('/mobil', [MobilController::class, 'mobilIndex']);
    Route::post('/mobil', [MobilController::class, 'mobilStore']);
    Route::put('/mobil/{id}', [MobilController::class, 'mobilUpdate']);
    Route::get('/mobil/{id}', [MobilController::class, 'mobilShow']);
    Route::delete('/mobil/{id}', [MobilController::class, 'mobilDestroy']);

    // Sales Route
    Route::get('/sales', [SalesController::class, 'index']);
    Route::post('/sales', [SalesController::class, 'store']);
  });
