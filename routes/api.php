<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItineraryController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login'])->name('login');
Route::get('/itineraries', [ItineraryController::class, 'index']);
Route::get('/itineraries/{id}', [ItineraryController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class,'logout']);
    Route::get('/user', [AuthController::class,'profile']);

    Route::post('/itineraries', [ItineraryController::class, 'store']);
    Route::put('/itineraries/{id}', [ItineraryController::class, 'update']);
    Route::delete('/itineraries/{id}', [ItineraryController::class, 'destroy']);
});

