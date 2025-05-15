<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\KomentarController;

Route::get('/', function () {
    return view('app');
});

Route::get('/home', [BarangController::class, 'index'])->name('home');

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

Route::get('/password', function () {
    return view('password');
})->name('password');


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