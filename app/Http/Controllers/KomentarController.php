<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use App\Models\Pembeli;
use App\Models\Penitip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomentarController extends Controller
{
    public function index($kode_produk)
    {
        $komentars = Komentar::where('kode_produk', $kode_produk)->with('pembeli')->get();
        return response()->json($komentars, 200);
    }

    public function store(Request $request, $kode_produk)
    {
        // Validate the request
        $request->validate([
            'komentar' => 'required|string|max:255',
            'alamat_email' => 'required|email|exists:pembeli,alamat_email' // Restrict to pembeli email
        ]);

        $alamat_email = $request->alamat_email;

        // Check if the email belongs to a pembeli
        $pembeli = Pembeli::where('alamat_email', $alamat_email)->first();
        if (!$pembeli) {
            return response()->json(['message' => 'Hanya pembeli yang dapat mengomentari produk'], 403);
        }

        // Create the comment
        $komentar = Komentar::create([
            'kode_produk' => $kode_produk,
            'alamat_email' => $alamat_email,
            'komentar' => $request->komentar
        ]);

        return response()->json(['message' => 'Komentar berhasil ditambahkan', 'data' => $komentar], 201);
    }
}