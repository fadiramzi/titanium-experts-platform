<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ExpertController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// if request recevied on /items, them route the request to index function inside ItemController
Route::post('/experts/signup', [ExpertController::class, 'signup']);
Route::post('/experts/verify', [ExpertController::class, 'verify']);


// refactor routes to use convention name of api resources
Route::post('/users', [UserController::class, 'add']);
Route::get('/users', [UserController::class, 'getList']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'delete']);






