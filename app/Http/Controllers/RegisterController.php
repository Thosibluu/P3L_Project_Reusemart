<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\OtpCode;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class RegisterController extends Controller
{
    // 1. Kirim OTP ke email pembeli
    public function sendOtp(Request $request)
    {
        $request->validate([
            'alamat_email' => 'required|email',
            'nama_pembeli' => 'required|string|max:255',
        ]);

        $email = $request->alamat_email;
        $nama  = $request->nama_pembeli;

        // Cek apakah email sudah terdaftar
        if (Pembeli::where('alamat_email', $email)->exists()) {
            return response()->json(['message' => 'Email sudah terdaftar!'], 422);
        }

        // Hapus OTP lama untuk email ini (khusus pembeli)
        OtpCode::where('email', $email)->where('type', 'pembeli')->delete();

        // Generate kode 6 digit
        $code = sprintf("%06d", mt_rand(0, 999999));

        // Simpan OTP
        OtpCode::create([
            'email'      => $email,
            'code'       => $code,
            'type'       => 'pembeli',
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        // Kirim email
        Mail::to($email)->send(new OtpMail($code, $nama));

        return response()->json([
            'message' => 'Kode OTP berhasil dikirim ke email Anda!',
            'debug_email' => $email // hapus ini nanti di production
        ], 200);
    }

    // 2. Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'alamat_email' => 'required|email',
            'code'         => 'required|size:6',
        ]);

        $otp = OtpCode::valid()
            ->where('email', $request->alamat_email)
            ->where('code', $request->code)
            ->where('type', 'pembeli')
            ->first();

        if (!$otp) {
            return response()->json([
                'message' => 'Kode OTP salah atau sudah kadaluarsa.'
            ], 400);
        }

        // Tandai sebagai sudah dipakai
        $otp->update(['used' => true]);

        return response()->json([
            'message' => 'OTP benar! Silakan lanjutkan registrasi.'
        ], 200);
    }

    // 3. Registrasi final setelah OTP benar
    public function registerPembeli(Request $request)
    {
        $request->validate([
            'nama_pembeli'           => 'required|string|max:255',
            'alamat_email'           => 'required|email|unique:pembeli,alamat_email',
            'nomor_telepon_pembeli'  => 'required|string',
            'password'               => 'required|min:8|confirmed',
            'otp'                    => 'required|size:6',
        ]);

        // Verifikasi OTP lagi (double protection)
        $verify = $this->verifyOtp(new Request([
            'alamat_email' => $request->alamat_email,
            'code'         => $request->otp,
        ]));

        if ($verify->getStatusCode() !== 200) {
            return $verify;
        }

        // Buat akun pembeli
        $pembeli = Pembeli::create([
            'nama_pembeli'          => $request->nama_pembeli,
            'alamat_email'          => $request->alamat_email,
            'nomor_telepon_pembeli' => $request->nomor_telepon_pembeli,
            'password'              => Hash::make($request->password),
            'total_poin'            => 0,
            'gambar'                => 'img/img1.jpg',
        ]);

        // Hapus semua OTP untuk email ini
        OtpCode::where('email', $request->alamat_email)->where('type', 'pembeli')->delete();

        return response()->json([
            'message' => 'Registrasi berhasil! Silakan login.',
            'user'    => [
                'nama'   => $pembeli->nama_pembeli,
                'email'  => $pembeli->alamat_email,
            ]
        ], 201);
    }
}