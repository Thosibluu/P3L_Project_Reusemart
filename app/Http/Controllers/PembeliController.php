<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembeli;
use App\Models\TransaksiPembelian; 
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Log;

class PembeliController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'alamat_email' => 'required|email|unique:pembeli,alamat_email',
            'password' => 'required|min:8',
            'nama_pembeli' => 'required|string|min:5|max:50',
            'nomor_telepon_pembeli' => 'required|regex:/^[0-9]+$/|digits_between:11,13',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pembeli = Pembeli::create([
            'alamat_email' => $request->alamat_email,
            'password' => Hash::make($request->password),
            'nama_pembeli' => $request->nama_pembeli,
            'nomor_telepon_pembeli' => $request->nomor_telepon_pembeli,
            'total_poin' => 0,
            'gambar' => 'img/img1.jpg', // kosong atau default image
        ]);

        return response()->json(['message' => 'Pendaftaran berhasil', 'Pembeli' => $pembeli,], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'alamat_email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $pembeli = Pembeli::where('alamat_email', $request->alamat_email)->first();

        if (!$pembeli || !Hash::check($request->password, $pembeli->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $token = $pembeli->createToken('pembeli-token', ['role:pembeli'])->plainTextToken;

        $userData = [
            'alamat_email' => $pembeli->alamat_email,
            'nama_pembeli' => $pembeli->nama_pembeli,
            'nomor_telepon_pembeli' => $pembeli->nomor_telepon_pembeli,
            'total_poin' => $pembeli->total_poin,
            'profile_image' => $pembeli->gambar,
        ];

        return response()->json([
            'user' => $userData,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => 'pembeli',
            'message' => 'Login berhasil'
        ], 200);
    }

    public function getProfile(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'nama' => $user->nama_pembeli,
            'email' => $user->alamat_email,
            'no_hp' => $user->nomor_telepon_pembeli,
            'foto' => $user->gambar,
            'total_poin' => $user->total_poin,
            'alamats' => $user->alamats,
        ]);
    }

    public function transaksiValid(Request $request)
    {
        try {
            //$search = $request->input('search', ''); // Default ke string kosong jika tidak ada search

            $donasi = TransaksiPembelian::where('status_pembelian', 'Sedang disiapkan')->get();

            // Format data untuk respons
            $donasi = $donasi->map(function ($item) {
                return [
                    'no_transaksi' => $item->id_transaksi_beli,
                    'tanggal_transaksi' => $item->tanggal_lunas ?? '-',
                    'total_transaksi' => $item->total_harga ?? '-',
                    'status_transaksi' => $item?->status_pembelian ?? '-',
                    
                ];
            });

            Log::info('donasiLaporan executed successfully, records: ' . $donasi->count());
            return response()->json($donasi);
        } catch (\Exception $e) {
            Log::error('Error in donasiLaporan: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout berhasil'], 200);
    }
}