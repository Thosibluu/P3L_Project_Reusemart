<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Organisasi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrganisasiController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_organisasi'         => 'required|string|max:255',
            'alamat_organisasi'       => 'required|string|max:255',
            'nomor_telpon_organisasi'=> 'required|regex:/^[0-9]+$/|digits_between:11,13',
            'password' => 'required|string|min:8',
        ]);

        $lastId = DB::table('organisasi')
            ->select(DB::raw("CAST(SUBSTRING(id_organisasi, 4) AS UNSIGNED) as id_num"))
            ->orderByDesc('id_num')
            ->value('id_num') ?? 11;

        $newId = 'ORG' . ($lastId + 1);

        DB::table('organisasi')->insert([
            'id_organisasi' => $newId,
            'nama_organisasi' => $validated['nama_organisasi'],
            'alamat_organisasi' => $validated['alamat_organisasi'],
            'nomor_telpon_organisasi' => $validated['nomor_telpon_organisasi'],
            'password' => bcrypt($validated['password']),
            'tanggal_tambah_organisasi_log' => now(),
        ]);

        return response()->json(['message' => 'Registrasi organisasi berhasil!'], 201);
    }
}