<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
   public function pegawaiLogin(Request $request)
    {
        $credentials = $request->only('id_pegawai', 'password');
        $pegawai = \App\Models\Pegawai::where('id_pegawai', $credentials['id_pegawai'])->first();

        if ($pegawai && Hash::check($credentials['password'], $pegawai->password)) {
            // Check if the id_pegawai indicates an admin (e.g., starts with "RM")
            if (!Str::startsWith($pegawai->id_pegawai, 'RM')) {
                return response()->json(['message' => 'Akses ditolak. Hanya admin yang diizinkan.'], 403);
            }

            $token = $pegawai->createToken('pegawai-token')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil!',
                'redirect' => '/admin',
                'token' => $token
            ]);
        }

        return response()->json(['message' => 'ID Pegawai atau password salah'], 401);
    }

    public function resetPegawaiPassword(Request $request)
{
    $request->validate(['id_pegawai' => 'required']);
    $pegawai = \App\Models\Pegawai::where('id_pegawai', $request->id_pegawai)->first();

    if ($pegawai) {
        $tanggal_lahir = Carbon::parse($pegawai->tanggal_lahir)->format('Ymd');
        $pegawai->update(['password' => Hash::make($tanggal_lahir)]);
        return response()->json(['message' => 'Password direset ke ' . $tanggal_lahir]);
    }
    return response()->json(['message' => 'Pegawai tidak ditemukan'], 401);
}
}
