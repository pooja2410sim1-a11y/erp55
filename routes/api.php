<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PermissionController;

/*
|--------------------------------------------------------------------------
| Authenticated User
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user()->load('roles.permissions');
});

/*
|--------------------------------------------------------------------------
| Role Management (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'permission:manage_roles'])->group(function () {

    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles/{role}', [RoleController::class, 'update']);
    Route::delete('/roles/{role}', [RoleController::class, 'destroy']);

    // Permissions listing + update
    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::post('/roles/{role}/permissions', [PermissionController::class, 'updateRolePermissions']);

});

/*
|--------------------------------------------------------------------------
| User Management (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'permission:manage_users'])->group(function () {

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

});