<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\MotorController;

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
    Route::get('/kendaraan/{id}', [KendaraanController::class, 'show']);
    Route::put('/kendaraan/{id}', [KendaraanController::class, 'update']);
    Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy']);

    // Motor Routes
    Route::get('/motor', [MotorController::class, 'motorIndex']);
    Route::post('/motor', [MotorController::class, 'motorStore']);
    Route::put('/motor/{id}', [MotorController::class, 'motorUpdate']);
    Route::delete('/motor/{id}', [MotorController::class, 'motorDestroy']);

    // Mobil Routes
    Route::get('/mobil', [MobilController::class, 'mobilIndex']);
    Route::post('/mobil', [MobilController::class, 'mobilStore']);
    Route::put('/mobil/{id}', [MobilController::class, 'mobilUpdate']);
    Route::delete('/mobil/{id}', [MobilController::class, 'mobilDestroy']);
  });
