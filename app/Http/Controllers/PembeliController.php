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
            'gambar' => 'img/img1.jpg',
        ]);

        log_activity(
            'pembeli',
            $pembeli->alamat_email,
            $pembeli->nama_pembeli,
            'Register',
            'Berhasil registrasi akun pembeli'
        );

        return response()->json(['message' => 'Pendaftaran berhasil', 'Pembeli' => $pembeli,], 201);
    }

    public function login(Request $request)
{
    $request->validate([
        'alamat_email' => 'required|email',
        'password'     => 'required|string'
    ]);

    $email   = $request->alamat_email;
    $pembeli = Pembeli::where('alamat_email', $email)->first();

    // 1. AKUN SUDAH TERKUNCI?
    if ($pembeli->locked) {
        log_activity('pembeli', $email, $pembeli->nama_pembeli ?? '-', 'Login Failed', 'Akun terkunci oleh sistem/admin');
        return response()->json(['message' => 'Akun Anda terkunci. Hubungi admin.'], 403);
    }

    // 2. EMAIL TIDAK ADA
    if (!$pembeli) {
        log_activity('pembeli', $email, null, 'Login Failed', 'Email tidak terdaftar');
        return response()->json(['message' => 'Email atau password salah'], 401);
    }

    // 3. PASSWORD SALAH
    // 3. PASSWORD SALAH
if (!Hash::check($request->password, $pembeli->password)) {
    // Selalu tambah attempt dulu
    $pembeli->increment('login_attempts');

    // Cek apakah sudah mencapai 3 kali
    if ($pembeli->login_attempts >= 3) {
        $pembeli->update(['locked' => true]);

        log_activity(
            'pembeli',
            $email,
            $pembeli->nama_pembeli,
            'Account Locked',
            'Akun terkunci otomatis setelah 3x gagal login (IP: ' . $request->ip() . ')'
        );

        return response()->json([
            'message' => 'Akun Anda terkunci karena 3x gagal login. Hubungi admin untuk membuka.'
        ], 403);
    }

    log_activity(
        'pembeli',
        $email,
        $pembeli->nama_pembeli,
        'Login Failed',
        "Gagal login (percobaan ke-{$pembeli->login_attempts})"
    );

    return response()->json(['message' => 'Email atau password salah'], 401);
}

    // 4. LOGIN BERHASIL → RESET SEMUA
    $pembeli->update(['login_attempts' => 0, 'locked' => false]);

    $token = $pembeli->createToken('pembeli-token', ['role:pembeli'])->plainTextToken;

    log_activity('pembeli', $email, $pembeli->nama_pembeli, 'Login', 'Login berhasil dari IP: ' . $request->ip());

    return response()->json([
        'message'      => 'Login berhasil',
        'access_token' => $token,
        'token_type'   => 'Bearer',
        'role'         => 'pembeli',
        'user'         => [
            'alamat_email'         => $pembeli->alamat_email,
            'nama_pembeli'         => $pembeli->nama_pembeli,
            'nomor_telepon_pembeli'=> $pembeli->nomor_telepon_pembeli,
            'total_poin'           => $pembeli->total_poin,
            'profile_image'        => $pembeli->gambar ?? asset('img/img1.jpg'),
        ]
    ]);
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
            $donasi = TransaksiPembelian::where('status_pembelian', 'Sedang disiapkan')->get();

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

    public function listPembeli()
    {
        try {
            $pembeli = Pembeli::select( 'alamat_email', 'nama_pembeli', 'nomor_telepon_pembeli', 'locked')->get();
            return response()->json($pembeli);
        } catch (\Exception $e) {
            Log::error('Error loading pembeli list: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal memuat data pembeli'], 500);
        }
    }

    public function unlockAkun(Request $request)
    {
        $request->validate([
            'alamat_email' => 'required|email|exists:pembeli,alamat_email'
        ]);

        $pembeli = Pembeli::where('alamat_email', $request->alamat_email)->firstOrFail();
        
        $pembeli->update([
            'locked' => false,
            'login_attempts' => 0
        ]);

        log_activity(
            user_type: 'admin',
            identifier: 'admin',
            nama: 'Admin',
            action: 'Unlock Akun',
            description: "Membuka akun pembeli: {$pembeli->alamat_email}"
        );

        return response()->json([
            'message' => 'Akun berhasil dibuka & percobaan login direset'
        ]);
    }

    // Lock akun berdasarkan email
    public function lockAkun(Request $request)
    {
        $request->validate([
            'alamat_email' => 'required|email|exists:pembeli,alamat_email'
        ]);

        $pembeli = Pembeli::where('alamat_email', $request->alamat_email)->firstOrFail();
        
        $pembeli->update([
            'locked' => true
        ]);

        log_activity(
            user_type: 'admin',
            identifier: 'admin',
            nama: 'Admin',
            action: 'Lock Akun',
            description: "Mengunci akun pembeli: {$pembeli->alamat_email}"
        );

        return response()->json([
            'message' => 'Akun berhasil dikunci'
        ]);
    }
}