<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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
    // Temporary bypass of authentication for testing
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

Route::get('/livecode', function () {
    return view('livecode');
})->name('livecode');

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