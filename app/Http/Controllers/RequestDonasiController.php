<?php

namespace App\Http\Controllers;

use App\Models\RequestDonasi;
use App\Models\Organisasi;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestDonasiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->input('search', '');

            $query = RequestDonasi::with('organisasi')
                ->where('status_request', 'menunggu')
                ->join('organisasi', 'request_donasi.id_organisasi', '=', 'organisasi.id_organisasi')
                ->select(
                    'request_donasi.id_organisasi',
                    'organisasi.nama_organisasi',
                    'organisasi.alamat_organisasi as alamat',
                    'request_donasi.kode_produk',
                    'request_donasi.detail_request'
                );

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('organisasi.nama_organisasi', 'like', "%$search%")
                      ->orWhere('request_donasi.id_organisasi', 'like', "%$search%")
                      ->orWhere('request_donasi.detail_request', 'like', "%$search%")
                      ->orWhere('request_donasi.kode_produk', 'like', "%$search%");
                });
            }

            $requests = $query->get();

            $requests = $requests->map(function ($item) {
                return [
                    'id_organisasi' => $item->id_organisasi ?? '-',
                    'nama_organisasi' => $item->nama_organisasi ?? '-',
                    'alamat_organisasi' => $item->alamat ?? '-',
                    'kode_produk' => $item->kode_produk?? '-',
                    'detail_request' => $item->detail_request ?? '-',
                ];
            });

            Log::info('requestDonasi executed successfully, records: ' . $requests->count());
            return response()->json($requests);
        } catch (\Exception $e) {
            Log::error('Error in requestDonasi: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server'], 500);
        }
    }
}