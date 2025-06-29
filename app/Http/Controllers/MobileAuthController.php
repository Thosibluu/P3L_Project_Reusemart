<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Pegawai;

class MobileAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');
        $user = null;
        $role = null;

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $user = Pembeli::where('alamat_email', $username)->first();
            if ($user) {
                $role = 'pembeli';
            }
        } elseif (preg_match('/^T\d+$/', $username)) {
            $user = Penitip::where('id_penitip', $username)->first();
            if ($user) {
                $role = 'penitip';
            }
        } elseif (preg_match('/^RM\d+$/', $username)) {
            $user = Pegawai::where('id_pegawai', $username)->first();
            if ($user) {
                if ($user->id_role === 'R33') {
                    $role = 'kurir';
                } elseif ($user->id_role === 'R55') {
                    $role = 'hunter';
                } else {
                    return response()->json([
                        'message' => 'Role pegawai tidak valid',
                    ], 401);
                }
            }
        }

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Password salah',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'role' => $role,
            'user' => [
                'id' => $user->id_penitip ?? $user->alamat_email ?? $user->id_pegawai,
                'name' => $user->nama_pembeli ?? $user->nama_penitip ?? $user->nama_pegawai,
                'role' => $role,
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil',
        ], 200);
    }

    public function getProfile(Request $request)
    {
        $user = $request->user();
        $role = $user->role;

        
        $pegawai = Pegawai::where('id_pegawai', $user->id_pegawai)->first();

        if (!$pegawai) {
            return response()->json(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        return response()->json([
            'nama_pegawai' => $pegawai->nama_pegawai,
            'id_pegawai' => $pegawai->id_pegawai,
            'no_telpon_pegawai' => $pegawai->no_telpon_pegawai,
            'alamat_pegawai' => $pegawai->alamat_pegawai,
            'tanggal_lahir' => $pegawai->tanggal_lahir? $pegawai->tanggal_lahir->format('Y-m-d') : null,
        ], 200);
    }
}