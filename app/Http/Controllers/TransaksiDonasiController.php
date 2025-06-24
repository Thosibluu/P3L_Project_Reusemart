<?php

namespace App\Http\Controllers;

use App\Models\TransaksiDonasi;
use App\Models\Barang;
use App\Models\Penitip;
use App\Models\Organisasi;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransaksiDonasiController extends Controller
{
    public function donasiLaporan(Request $request)
    {
        try {
            $search = $request->input('search', ''); // Default ke string kosong jika tidak ada search

            $query = TransaksiDonasi::with(['barang.detailPenitipan.transaksiPenitipan.penitip', 'organisasi'])
                ->join('barang', 'transaksi_donasi.kode_produk', '=', 'barang.kode_produk')
                ->leftJoin('organisasi', 'transaksi_donasi.id_organisasi', '=', 'organisasi.id_organisasi')
                ->select(
                    'transaksi_donasi.kode_produk',
                    'transaksi_donasi.tanggal_donasi',
                    'transaksi_donasi.nama_penerima',
                    'organisasi.nama_organisasi',
                    'transaksi_donasi.hunter_related',
                );

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('barang.nama_produk', 'like', "%$search%")
                      ->orWhere('transaksi_donasi.kode_produk', 'like', "%$search%")
                      ->orWhere('transaksi_donasi.nama_penerima', 'like', "%$search%")
                      ->orWhere('organisasi.nama_organisasi', 'like', "%$search%");
                });
            }

            $donasi = $query->get();

            // Format data untuk respons
            $donasi = $donasi->map(function ($item) {
                $penitip = $item->barang?->detailPenitipan?->transaksiPenitipan?->penitip;

                //$pegawai = Pegawai::find($item->id_pegawai);
                if($item->hunter_related === "YES"){
                    $pegawai = Pegawai::where('id_role', 'R55')
                  ->first();
                }
                

                return [
                    'kode_produk' => $item->kode_produk,
                    'nama_produk' => $item->barang?->nama_produk ?? '-',
                    'id_penitip' => $penitip?->id_penitip ?? '-',
                    'nama_penitip' => $penitip?->nama_penitip ?? '-',
                    'tanggal_donasi' => $item->tanggal_donasi ? $item->tanggal_donasi->format('d/m/Y') : '-',
                    'nama_organisasi' => $item->organisasi?->nama_organisasi ?? $item->nama_organisasi ?? '-',
                    'nama_penerima' => $item->nama_penerima ?? '-',
                    'nama_hunter' => $pegawai?->nama_pegawai ?? '-',
                ];
            });

            Log::info('donasiLaporan executed successfully, records: ' . $donasi->count());
            return response()->json($donasi);
        } catch (\Exception $e) {
            Log::error('Error in donasiLaporan: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }
}