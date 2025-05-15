<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Komentar;
use App\Models\DetailPenitipan;
use App\Models\TransaksiPenitipan;
use App\Models\Penitip;
use App\Models\Pembeli;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        $kategoris = Kategori::all();
        return view('home', compact('barangs', 'kategoris'));
    }

    public function byKategori(Request $request, $id = null)
    {
        $barangs = $id ? Barang::where('kategori_id', $id)->get() : Barang::all();
        return response()->json($barangs);
    }

    public function show($kode_produk)
    {
        $barang = Barang::where('kode_produk', $kode_produk)->firstOrFail();
        $komentars = Komentar::where('kode_produk', $kode_produk)->with('pembeli')->get();

        // Find penitip
        $detailPenitipan = DetailPenitipan::where('kode_produk', $kode_produk)->first();
        $penitipName = 'Penitip Misterius';
        if ($detailPenitipan) {
            $transaksiPenitipan = TransaksiPenitipan::where('id_transaksi_titip', $detailPenitipan->id_transaksi_titip)->first();
            if ($transaksiPenitipan) {
                $penitip = Penitip::where('id_penitip', $transaksiPenitipan->id_penitip)->first();
                $penitipName = $penitip ? $penitip->nama_penitip : 'Penitip Misterius';
            }
        }

        return view('barang', compact('barang', 'komentars', 'penitipName'));
    }

    public function apiShow($kode_produk)
    {
        $barang = Barang::where('kode_produk', $kode_produk)->firstOrFail();
        return response()->json($barang, 200);
    }

}