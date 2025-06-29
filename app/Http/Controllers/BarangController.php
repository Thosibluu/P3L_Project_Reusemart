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

    public function index2()
    {
        $barangs = Barang::all();
        $kategoris = Kategori::all();
        return view('app', compact('barangs', 'kategoris'));
    }

    public function apiIndex(Request $request)
    {
        try {
            $barangs = Barang::select('kode_produk as id_barang', 'nama_produk', 'harga', 'gambar')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $barangs,
                'message' => 'Produk berhasil diambil',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil produk: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function byKategori(Request $request, $id = null)
    {
        $barangs = $id ? Barang::where('id_kategori', $id)->get() : Barang::all();
        return response()->json($barangs);
    }

    public function show($kode_produk)
    {
        $barang = Barang::where('kode_produk', $kode_produk)->firstOrFail();
        $komentars = Komentar::where('kode_produk', $kode_produk)->with('pembeli')->get();

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
        return response()->json([
            'success' => true,
            'data' => [
                'id_barang' => $barang->kode_produk,
                'nama_produk' => $barang->nama_produk,
                'harga' => $barang->harga,
                'gambar' => $barang->gambar,
            ],
            'message' => 'Produk ditemukan',
        ], 200);
    }
}