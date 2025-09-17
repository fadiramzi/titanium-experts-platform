<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ExpertController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// if request recevied on /items, them route the request to index function inside ItemController
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/verify', [AuthController::class, 'verify']);

// refactor routes to use convention name of api resources
Route::middleware(['cookie.filter', 'auth:sanctum'])->group(function () {
    // protected
    Route::post('/users', [UserController::class, 'add']);
    Route::get('/users', [UserController::class, 'getList']);
    Route::get('/users/me',[UserController::class, 'me']);

    Route::post('/bookings', [BookingController::class, 'book']);
    Route::get('/bookings/me', [BookingController::class, 'me']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'delete']);

});







