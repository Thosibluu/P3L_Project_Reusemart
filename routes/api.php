<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KomentarController;

Route::post('/pembeli-register', [PembeliController::class, 'register']);
Route::post('/penitip-register', [PenitipController::class, 'register']);
Route::post('/organisasi-register', [OrganisasiController::class, 'register']);


    Route::post('/pembeli-login', [PembeliController::class, 'login']);
    Route::post('/penitip-login', [PenitipController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/profile', [PembeliController::class, 'getProfile'])->name('api.profile.pembeli');
    Route::get('/profile-penitip', [PenitipController::class, 'getProfile'])->name('api.profile.penitip');

    

    Route::post('/pembeli-logout', [PembeliController::class, 'logout']);
    Route::post('/penitip-logout', [PenitipController::class, 'logout']);

    Route::get('/alamats', [AlamatController::class, 'index']);
    Route::post('/alamats', [AlamatController::class, 'store']);
    Route::post('/alamats/{id}', [AlamatController::class, 'update']);
    Route::delete('/alamats/{id}', [AlamatController::class, 'destroy']);
    Route::post('/alamats/{id}/set-utama', [AlamatController::class, 'setUtama']);
    Route::post('/komentar/{kode_produk}', [KomentarController::class, 'store']);
    Route::get('/komentar/{kode_produk}', [KomentarController::class, 'index']);
});

Route::get('/barang/kategori/{id?}', [BarangController::class, 'byKategori']);

Route::get('/barang/{kode_produk}', [BarangController::class, 'apiShow']);


