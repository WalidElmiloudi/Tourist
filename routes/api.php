<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ItineraryController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/itineraries', [ItineraryController::class, 'index']);
Route::get('/itineraries/{id}', [ItineraryController::class, 'show']);

Route::get('/itineraries/{itinerary}/destinations', [DestinationController::class, 'index']);

Route::get('/destinations/{destination}/activities', [ActivityController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'profile']);

    Route::post('/itineraries', [ItineraryController::class, 'store']);
    Route::put('/itineraries/{id}', [ItineraryController::class, 'update']);
    Route::delete('/itineraries/{id}', [ItineraryController::class, 'destroy']);

    Route::post('/itineraries/{itinerary}/destinations', [DestinationController::class, 'store']);
    Route::put('/destinations/{destination}', [DestinationController::class, 'update']);
    Route::delete('/destinations/{destination}', [DestinationController::class, 'destroy']);

    Route::post('/destinations/{destination}/activities', [ActivityController::class, 'store']);
    Route::put('/activities/{activity}', [ActivityController::class, 'update']);
    Route::delete('/activities/{activity}', [ActivityController::class, 'destroy']);

    Route::post('/favorites/{itinerary}', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{itinerary}', [FavoriteController::class, 'destroy']);
    Route::get('/favorites', [FavoriteController::class, 'index']);
});
