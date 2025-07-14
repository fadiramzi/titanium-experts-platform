<?php

use App\Http\Controllers\Api\ExpertController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

