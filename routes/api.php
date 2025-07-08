<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\Role;

Route::post('/register', [AuthController::class, 'register']); 
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api', 'role:pelanggan')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:api', 'role:admin'])->group(function () { 
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:api', 'role:petugas'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

});