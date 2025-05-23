<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Penitip;
use App\Models\TransaksiPenitipan;
use App\Models\DetailPenitipan;
use App\Models\Barang;
use Laravel\Sanctum\PersonalAccessToken;


class PenitipController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_penitip' => 'required|string|max:50',
            'alamat_penitip' => 'required|string|max:255',
            'nomor_telpon_penitip' => 'required|regex:/^[0-9]+$/|digits_between:11,13',
            'password' => 'required|string|min:8',
        ]);

        // Auto-increment id format: T12, T13, ...
        $lastId = DB::table('penitip')
            ->select(DB::raw("CAST(SUBSTRING(id_penitip, 2) AS UNSIGNED) as id_num"))
            ->orderByDesc('id_num')
            ->value('id_num') ?? 11;

        $newId = 'T' . ($lastId + 1);

        DB::table('penitip')->insert([
            'id_penitip' => $newId,
            'nama_penitip' => $validated['nama_penitip'],
            'alamat_penitip' => $validated['alamat_penitip'],
            'nomor_telpon_penitip' => $validated['nomor_telpon_penitip'],
            'password' => bcrypt($validated['password']),
            'saldo' => 100000,
            'total_poin' => 0,
            'gambar' => 'img/img1.jpg',
            'tanggal_tambah_penitip_log' => now(),
            'user_penitip_log' => now(), // Atau ganti sesuai user_id yang login jika ada
        ]);

        return response()->json(['message' => 'Registrasi penitip berhasil!'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'id_penitip' => 'required|string',
            'password' => 'required|string'
        ]);

        $penitip = Penitip::where('id_penitip', $request->id_penitip)->first();

        if (!$penitip || !Hash::check($request->password, $penitip->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

            // Create a Sanctum token with abilities
            $token = $penitip->createToken('penitip-token', ['role:penitip'])->plainTextToken;

            $userData = [
                'id_penitip' => $penitip->id_penitip,
                'nama_penitip' => $penitip->nama_penitip,
                'nomor_telepon_penitip' => $penitip->nomor_telpon_penitip,
                'alamat_penitip' => $penitip->alamat_penitip,
                'saldo' => $penitip->saldo,
                'total_poin' => $penitip->total_poin,
                'profile_image' => $penitip->gambar,
            ];

            return response()->json([
                'user' => $userData,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => 'penitip',
                'message' => 'Login berhasil'
            ], 200);

    }

    public function getProfile(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Fetch penitipan history with details
        $penitipanHistory = TransaksiPenitipan::with(['detailPenitipan.barang'])
            ->where('id_penitip', $user->id_penitip)
            ->get()
            ->flatMap(function ($transaksi) {
                return $transaksi->detailPenitipan->map(function ($detail) use ($transaksi) {
                    return [
                        'id_transaksi' => $transaksi->id_transaksi_titip, // Updated to match primary key
                        'tanggal_penitipan' => $transaksi->tanggal_penitipan,
                        'nama_barang' => $detail->barang->nama_produk ?? 'N/A',
                        'harga' => $detail->barang->harga ?? 0,
                        'status_perpanjang' => $detail->barang->status_perpanjang, // Added fallback
                        'batas_penitipan' => $transaksi->batas_penitipan, // Added fallback
                    ];
                })->all(); // Ensure flatMap returns an array
            })->values();

        return response()->json([
            'nama' => $user->nama_penitip,
            'id_penitip' => $user->id_penitip,
            'alamat' => $user->alamat_penitip,
            'no_hp' => $user->nomor_telpon_penitip,
            'saldo' => $user->saldo,
            'total_poin' => $user->total_poin,
            'foto' => $user->gambar,
            'role' => 'penitip',
            'penitipan_history' => $penitipanHistory,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout berhasil'], 200);
    }
}