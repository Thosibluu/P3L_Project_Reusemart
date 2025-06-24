<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TransaksiPembelianController;
use App\Http\Controllers\DetailPembelianController;

Route::get('/login', [PembeliController::class, 'showLoginForm'])->name('login');
Route::post('/pembeli-login', [PembeliController::class, 'login']);
Route::post('/penitip-login', [PenitipController::class, 'login']);
Route::post('/pembeli-register', [PembeliController::class, 'register']);
Route::post('/penitip-register', [PenitipController::class, 'register']);
Route::post('/organisasi-register', [OrganisasiController::class, 'register']);
Route::post('/pegawai-login', [PegawaiController::class, 'pegawaiLogin']);
Route::post('/reset-pegawai-password', [PegawaiController::class, 'resetPegawaiPassword']);

Route::middleware('auth')->group(function () {
    Route::get('/home', [BarangController::class, 'index'])->name('home');
    Route::get('/profile', [PembeliController::class, 'getProfile'])->name('profile.pembeli');
    Route::get('/profile-penitip', [PenitipController::class, 'getProfile'])->name('profile.penitip');
    Route::post('/pembeli-logout', [PembeliController::class, 'logout'])->name('logout');
    Route::post('/penitip-logout', [PenitipController::class, 'logout']);

    Route::get('/alamats', [AlamatController::class, 'index'])->name('alamats.index');
    Route::post('/alamats', [AlamatController::class, 'store'])->name('alamats.store');
    Route::post('/alamats/{id}', [AlamatController::class, 'update'])->name('alamats.update');
    Route::delete('/alamats/{id}', [AlamatController::class, 'destroy'])->name('alamats.destroy');
    Route::post('/alamats/{id}/set-utama', [AlamatController::class, 'setUtama'])->name('alamats.setUtama');
    Route::post('/komentar/{kode_produk}', [KomentarController::class, 'store'])->name('komentar.store');
    Route::get('/komentar/{kode_produk}', [KomentarController::class, 'index'])->name('komentar.index');

    Route::get('/barang/kategori/{id?}', [BarangController::class, 'byKategori'])->name('barang.byKategori');
    Route::get('/produk/{kode_produk}', [BarangController::class, 'show'])->name('produk.show');

    Route::get('/checkout-buy', [TransaksiPembelianController::class, 'showCheckoutBuy'])->name('checkout.buy');
    Route::get('/checkout-cart', [TransaksiPembelianController::class, 'showCheckoutCart'])->name('checkout.cart');
    Route::post('/checkout', [TransaksiPembelianController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/konfirmasi/{transactionId}', [TransaksiPembelianController::class, 'showKonfirmasi'])->name('konfirmasi');
    Route::post('/konfirmasi/{transactionId}', [TransaksiPembelianController::class, 'confirm'])->name('konfirmasi.process');
    Route::post('/cancel/{transactionId}', [TransaksiPembelianController::class, 'cancel'])->name('cancel.transaction');
    Route::get('/transaksi/{transactionId}/details', [DetailPembelianController::class, 'index'])->name('transaksi.details');

    Route::prefix('organisasi')->group(function () {
        Route::get('/', [OrganisasiController::class, 'index'])->name('organisasi.index');
        Route::put('{id}', [OrganisasiController::class, 'update'])->name('organisasi.update');
        Route::delete('{id}', [OrganisasiController::class, 'destroy'])->name('organisasi.destroy');
    });
});

Route::get('/', function () {
    return view('app');
});

Route::get('/home', [BarangController::class, 'index'])->name('home');
Route::get('/', [BarangController::class, 'index2'])->name('app');

Route::get('/register/pembeli', function () {
    return view('register');
})->name('register');

Route::get('/register/penitip', function () {
    return view('register');
})->name('register');

Route::get('/register/organisasi', function () {
    return view('register');
})->name('register');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/admin', function (Request $request) {
    return view('admin');
})->name('admin');

Route::get('/admin-login', function () {
    return view('admin.login');
})->name('admin-login');

Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return redirect('/login');
})->middleware('auth:sanctum')->name('logout');

Route::get('/reset-password', function () {
    return view('reset-password');
})->name('reset-password');

Route::get('/profil', function () {
    return view('pembeli.profil');
})->name('profil');

Route::get('/profil-penitip', function () {
    return view('penitip.profil');
})->name('profil.penitip');

Route::post('/logout', function () {
    session()->flush();
    return redirect('/app')->with('success', 'Berhasil logout');
});

Route::get('/kategori/{id?}', [BarangController::class, 'byKategori'])->name('kategori');

Route::get('/produk/{kode_produk}', [BarangController::class, 'show'])->name('produk.show');

Route::get('/password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/test-session', function () {
    session(['test' => 'Session working']);
    return session('test');
});

// Routes for Checkout and Konfirmasi
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/checkout-buy', [TransaksiPembelianController::class, 'showCheckoutBuy'])->name('checkout.buy');
    Route::get('/checkout-cart', [TransaksiPembelianController::class, 'showCheckoutCart'])->name('checkout.cart');
    Route::post('/checkout/process', [TransaksiPembelianController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/konfirmasi/{transactionId}', [TransaksiPembelianController::class, 'showKonfirmasi'])->name('konfirmasi');
    Route::post('/konfirmasi/{transactionId}', [TransaksiPembelianController::class, 'confirm'])->name('konfirmasi.process');
    Route::post('/cancel/{transactionId}', [TransaksiPembelianController::class, 'cancel'])->name('cancel.transaction');
    Route::get('/produk/{kode_produk}/beli', [TransaksiPembelianController::class, 'buyNow'])->name('buy.now');
});