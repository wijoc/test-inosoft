<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;

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
    Route::get('/kendaraan', [KendaraanController::class, 'index'])->middleware('jauth');
    Route::post('/kendaraan', [KendaraanController::class, 'store'])->middleware('jauth');
    Route::put('/kendaraan/{id}', [KendaraanController::class, 'update'])->middleware('jauth');
    Route::get('/kendaraan/{id}', [KendaraanController::class, 'show'])->middleware('jauth');
    Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy'])->middleware('jauth');

    // Stock Routes
    Route::get('/stock/kendaraan/all', [KendaraanController::class, 'stockAll'])->middleware('jauth');
    Route::get('/stock/kendaraan/{id}', [KendaraanController::class, 'stockKendaraan'])->middleware('jauth');

    // Motor Routes
    Route::get('/motor', [MotorController::class, 'motorIndex'])->middleware('jauth');
    Route::post('/motor', [MotorController::class, 'motorStore'])->middleware('jauth');
    Route::put('/motor/{id}', [MotorController::class, 'motorUpdate'])->middleware('jauth');
    Route::get('/motor/{id}', [MotorController::class, 'motorShow'])->middleware('jauth');
    Route::delete('/motor/{id}', [MotorController::class, 'motorDestroy'])->middleware('jauth');

    // Mobil Routes
    Route::get('/mobil', [MobilController::class, 'mobilIndex'])->middleware('jauth');
    Route::post('/mobil', [MobilController::class, 'mobilStore'])->middleware('jauth');
    Route::put('/mobil/{id}', [MobilController::class, 'mobilUpdate'])->middleware('jauth');
    Route::get('/mobil/{id}', [MobilController::class, 'mobilShow'])->middleware('jauth');
    Route::delete('/mobil/{id}', [MobilController::class, 'mobilDestroy'])->middleware('jauth');

    // Sales Route
    Route::get('/sales', [SalesController::class, 'index'])->middleware('jauth');
    Route::get('/sales/kendaraan/{id}', [SalesController::class, 'showSalesKendaraan'])->middleware('jauth');
    Route::get('/sales/{id}', [SalesController::class, 'show'])->middleware('jauth');
    Route::post('/sales', [SalesController::class, 'store'])->middleware('jauth');

    // User Routes
    Route::post('/registration', [UserController::class, 'store']);
    Route::post('/login', [UserController::class, 'login']);
  });
