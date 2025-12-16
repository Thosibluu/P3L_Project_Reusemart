<?php
// app/Helpers/LogHelper.php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

if (!function_exists('log_activity')) {
    /**
     * Log aktivitas dengan format nama (identifier) yang super informatif
     */
    function log_activity(
        string  $user_type,
        ?string $identifier = null,   // email untuk pembeli, ID untuk yang lain
        ?string $nama = null,
        string  $action = 'Unknown',
        ?string $description = null
    ): void {
        try {
            // 1. Tentukan identifier yang disimpan di user_id
            $user_id = match ($user_type) {
                'pembeli'     => $identifier ?? 'unknown@email.com',
                'penitip'     => $identifier ?? 'T??',
                'organisasi'  => $identifier ?? 'O??',
                'pegawai'     => $identifier ?? 'RM??',
                default       => $identifier ?? 'unknown',
            };

            // 2. Ambil nama otomatis kalau kosong
            if (blank($nama) && $identifier) {
                $nama = match ($user_type) {
                    'pembeli'     => \App\Models\Pembeli::where('alamat_email', $identifier)->value('nama_pembeli'),
                    'penitip'     => \App\Models\Penitip::where('id_penitip', $identifier)->value('nama_penitip'),
                    'organisasi'  => \App\Models\Organisasi::where('id_organisasi', $identifier)->value('nama_organisasi'),
                    'pegawai'     => \App\Models\Pegawai::where('id_pegawai', $identifier)->value('nama_pegawai'),
                    default       => null,
                };
            }

            // 3. Format kolom "nama" jadi: Nama Lengkap (email/ID)
            $display_name = $nama 
                ? trim($nama) . ' (' . $user_id . ')'
                : 'Unknown User (' . $user_id . ')';

            DB::table('activity_logs')->insert([
                'user_type'    => $user_type,
                'user_id'      => $user_id,                    // murni identifier (untuk filter nanti)
                'nama'         => $display_name,               // ← INI YANG DITAMPILKAN DI DASHBOARD
                'action'       => $action,
                'description'  => $description,
                'ip_address'   => Request::ip(),
                'user_agent'   => Request::header('User-Agent') ?: Request::userAgent(),
                'logged_at'    => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('Gagal mencatat activity log: ' . $e->getMessage());
        }
    }
}