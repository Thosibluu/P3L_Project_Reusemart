<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPembelian;

class DetailPembelianController extends Controller
{
    public function index($transactionId)
    {
        $details = DetailTransaksi::where('id_transaksi_beli', $transactionId)->get();
        return response()->json($details);
    }
}