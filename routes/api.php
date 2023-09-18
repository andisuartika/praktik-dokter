<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PemeriksaanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/store-pasien', [PemeriksaanController::class, 'storePasien']);
    Route::post('/store-periksa', [PemeriksaanController::class, 'storePeriksa']);
    Route::get('/get-pasien', [PemeriksaanController::class, 'getPasien']);
    Route::get('/get-riwayat', [PemeriksaanController::class, 'riwayatPasien']);
    Route::get('/get-history', [PemeriksaanController::class, 'history']);
});

// AUTH
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
