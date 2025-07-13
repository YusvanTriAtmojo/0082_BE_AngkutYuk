<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PesananController;
use App\Models\Role;

Route::post('/register', [AuthController::class, 'register']); 
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api', 'role:pelanggan')->group(function () {
    Route::post('/logout', [AuthControllesr::class, 'logout']);
    Route::get('/pelanggan/profile', [PelangganController::class, 'getProfile']);
    Route::put('/pelanggan/update', [PelangganController::class, 'updateByUserId']);
    Route::post('/pelanggan/update-foto', [PelangganController::class, 'updateFoto']);
    Route::get('/pelanggan/kategori', [KategoriController::class, 'index']);

    Route::post('/pelanggan/pesanan', [PesananController::class, 'store']);
    Route::get('/pelanggan/pesanan', [PesananController::class, 'index']); 
    Route::delete('/pelanggan/pesanan/{id}', [PesananController::class, 'destroy']);
});

Route::middleware(['auth:api', 'role:admin'])->group(function () { 
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/admin/kategori', [KategoriController::class, 'index']);
    Route::post('/admin/kategori', [KategoriController::class, 'store']);
    Route::put('admin/kategori/{id_kategori}', [KategoriController::class, 'update']);
    Route::delete('admin/kategori/{id}', [KategoriController::class, 'destroy']);

    Route::get('/admin/kendaraan', [KendaraanController::class, 'index']);
    Route::post('/admin/kendaraan', [KendaraanController::class, 'store']);
    Route::put('admin/kendaraan/{id_kendaraan}', [KendaraanController::class, 'update']);
    Route::delete('admin/kendaraan/{id}', [KendaraanController::class, 'destroy']);

    Route::get('/admin/petugas', [PetugasController::class, 'index']);
    Route::post('/admin/petugas', [PetugasController::class, 'store']);
    Route::put('admin/petugas/{id}', [PetugasController::class, 'update']);
    Route::delete('admin/petugas/{id}', [PetugasController::class, 'destroy']);

    Route::get('/admin/pesanan', [PesananController::class, 'all']);
    Route::put('/admin/pesanan/{id}', [PesananController::class, 'update']);
    Route::get('/admin/pesanan/{id}', [PesananController::class, 'getpesananid']);
});

Route::middleware(['auth:api', 'role:petugas'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/petugas/pesanan', [PesananController::class, 'index_petugas']);
    Route::get('/petugas/profile', [PetugasController::class, 'getProfile']);
    Route::put('/petugas/pesanan/{id}', [PesananController::class, 'update']);
    Route::post('/petugas/pesanan/{id}/bukti', [PesananController::class, 'uploadBuktiSelesai']);
});