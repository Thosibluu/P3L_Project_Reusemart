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
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TransaksiPembelianController;
use App\Http\Controllers\DetailPembelianController;
use App\Http\Controllers\MobileAuthController;
use App\Http\Controllers\RequestDonasiController;
use App\Http\Controllers\TransaksiDonasiController;
use App\Http\Controllers\TransaksiPenitipanController;

Route::post('/pembeli-register', [PembeliController::class, 'register']);
Route::post('/penitip-register', [PenitipController::class, 'register']);
Route::post('/organisasi-register', [OrganisasiController::class, 'register']);

Route::post('/pembeli-login', [PembeliController::class, 'login']);
Route::post('/penitip-login', [PenitipController::class, 'login']);
Route::post('/pegawai-login', [PegawaiController::class, 'pegawaiLogin']);
Route::post('/reset-pegawai-password', [PegawaiController::class, 'resetPegawaiPassword']);

Route::post('/login', [MobileAuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', [PegawaiController::class, 'getUser']);
Route::middleware('auth:sanctum')->post('/logout', [MobileAuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/profilestaff', [MobileAuthController::class, 'getProfile']);
Route::middleware('auth:sanctum')->get('/transaksi-pembelian/courier-tasks', [TransaksiPembelianController::class, 'getCourierTasks']);
Route::middleware('auth:sanctum')->post('/transaksi-pembelian/complete/{transactionId}', [TransaksiPembelianController::class, 'completeDelivery']);
Route::middleware('auth:sanctum')->get('/products', [BarangController::class, 'apiIndex']);

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

    Route::get('/profiles', [TransaksiPembelianController::class, 'showProfile']);
    Route::post('/checkout', [TransaksiPembelianController::class, 'processCheckout']);
    Route::post('/konfirmasi/{transactionId}', [TransaksiPembelianController::class, 'confirm']);
    Route::post('/konfirmasi/{transactionId}/cancel', [TransaksiPembelianController::class, 'cancel']);
    Route::get('/transaksi/{transactionId}/details', [DetailPembelianController::class, 'index']);
});

Route::get('/barang/kategori/{id?}', [BarangController::class, 'byKategori']);
Route::get('/barang/{kode_produk}', [BarangController::class, 'apiShow']);
Route::get('/transaksi-valid', [PembeliController::class, 'transaksiValid']);

Route::prefix('organisasi')->group(function () {
    Route::get('/', [OrganisasiController::class, 'index']);
    Route::put('{id}', [OrganisasiController::class, 'update']);
    Route::delete('{id}', [OrganisasiController::class, 'destroy']);
});

Route::get('/request-donasi', [RequestDonasiController::class, 'index']);
Route::get('/donasi-laporan', [TransaksiDonasiController::class, 'donasiLaporan']);

Route::get('/transaksi', [TransaksiPembelianController::class, 'index']);
Route::post('/transaksi/validate/{id}', [TransaksiPembelianController::class, 'validateTransaction']);

Route::get('/penitip', [TransaksiPenitipanController::class, 'getPenitipList']);
Route::get('/penitipan-transactions', [TransaksiPenitipanController::class, 'index']);