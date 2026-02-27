<?php

use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user()->load('roles.permissions');
});

Route::middleware(['auth', 'permission:dashboard.view'])->get('/users/stats', [UserController::class, 'stats']);

Route::middleware(['auth', 'permission:user.view'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
});

Route::middleware(['auth', 'permission:user.add'])->post('/users', [UserController::class, 'store']);
Route::middleware(['auth', 'permission:user.edit'])->put('/users/{user}', [UserController::class, 'update']);
Route::middleware(['auth', 'permission:user.delete'])->delete('/users/{user}', [UserController::class, 'destroy']);

Route::middleware(['auth', 'permission:role.view'])->get('/roles', [RoleController::class, 'index']);
Route::middleware(['auth', 'permission:role.add'])->post('/roles', [RoleController::class, 'store']);
Route::middleware(['auth', 'permission:role.edit'])->put('/roles/{role}', [RoleController::class, 'update']);
Route::middleware(['auth', 'permission:role.delete'])->delete('/roles/{role}', [RoleController::class, 'destroy']);

Route::middleware(['auth', 'permission:manage_permissions'])->group(function () {
    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::post('/roles/{role}/permissions', [PermissionController::class, 'updateRolePermissions']);
});
Route::middleware(['auth', 'permission:manage_roles'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::put('/roles/{role}', [RoleController::class, 'update']);
    Route::delete('/roles/{role}', [RoleController::class, 'destroy']);

    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::post('/roles/{role}/permissions', [PermissionController::class, 'updateRolePermissions']);
});

Route::middleware(['auth', 'permission:manage_users'])->group(function () {
    Route::get('/users/stats', [UserController::class, 'stats']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});
