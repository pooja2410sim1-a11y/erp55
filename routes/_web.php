<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/user', [AuthController::class, 'user']);

});

/*
|--------------------------------------------------------------------------
| SPA Catch-All Route
|--------------------------------------------------------------------------
*/

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');