<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPenitipan;
use App\Models\TransaksiPenitipan;
use App\Models\Penitip;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransaksiPenitipanController extends Controller
{
    public function index(Request $request)
    {
        $penitipId = $request->input('penitip_id');
        $month = $request->input('month');
        $year = $request->input('year');

        $query = TransaksiPenitipan::with(['penitip', 'detailPenitipan.barang'])
            ->whereNotNull('tanggal_laku'); // Hanya transaksi yang sudah laku

        if ($penitipId) {
            $query->where('id_penitip', $penitipId);
        }
        if ($month && $year) {
            $query->whereMonth('tanggal_penitipan', $month)
                  ->whereYear('tanggal_penitipan', $year);
        } elseif ($month) {
            $query->whereMonth('tanggal_penitipan', $month);
        } elseif ($year) {
            $query->whereYear('tanggal_penitipan', $year);
        }

        $allTransactions = []; // Array untuk menyimpan semua data transaksi

        $transactions = $query->get()->map(function ($transaction) use (&$allTransactions) {
            $details = $transaction->detailPenitipan;

            foreach ($details as $detail) {
                $barang = $detail->barang;
                $tanggalMasuk = $transaction->tanggal_penitipan;
                $tanggalLaku = $transaction->tanggal_laku;
                if (!$tanggalMasuk || !$tanggalLaku) {
                    continue; // Lewati jika tanggal tidak valid
                }
                $harga = $barang->harga ?? 0;

                // Hitung Harga Jual Bersih
                $daysDiff = $tanggalLaku->diffInDays($tanggalMasuk);
                $discountPercentage = $daysDiff > 30 ? 0.30 : 0.20;
                $hargaJualBersih = $harga * (1 - $discountPercentage);

                // Hitung Bonus Terjual Cepat
                $bonusTerjualCepat = $daysDiff < 7 ? $harga * 0.05 : 0;

                // Hitung Pendapatan
                $pendapatan = $hargaJualBersih + $bonusTerjualCepat;

                $allTransactions[] = [
                    'kode_produk' => $barang->kode_produk,
                    'nama_produk' => $barang->nama_produk,
                    'tanggal_masuk' => $tanggalMasuk->format('d/m/Y'),
                    'tanggal_laku' => $tanggalLaku ? $tanggalLaku->format('d/m/Y') : '-',
                    'harga_jual_bersih' => round($hargaJualBersih, 2),
                    'bonus_terjual_cepat' => round($bonusTerjualCepat, 2),
                    'pendapatan' => round($pendapatan, 2),
                ];
            }

            return null; // Tidak perlu return di dalam map karena kita gunakan $allTransactions
        });

        return response()->json($allTransactions); // Kembalikan array yang sudah dikumpulkan
    }

    public function getPenitipList()
    {
        $penitips = Penitip::select('id_penitip', 'nama_penitip')->get();
        return response()->json($penitips);
    }
}