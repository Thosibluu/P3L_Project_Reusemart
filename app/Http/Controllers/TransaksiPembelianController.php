<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiPembelian;
use App\Models\DetailPembelian;
use App\Models\Pegawai;
use App\Models\Barang;
use App\Models\Alamat;
use App\Models\Pembeli;
use Carbon\Carbon;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TransaksiPembelianController extends Controller
{

    public function showProfile(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'nama' => $user->nama_pembeli,
            'email' => $user->alamat_email,
            'no_hp' => $user->nomor_telepon_pembeli,
            'foto' => $user->gambar,
            'total_poin' => $user->total_poin,
            'alamats' => $user->alamats,
            'role' => 'pembeli', // Pastikan relasi alamats sudah di-load
        ]);
    }

    public function processCheckout(Request $request)
{
    try {
        \Log::info('Starting processCheckout', [
            'user' => $request->user() ? $request->user()->toArray() : null,
            'cart_data' => $request->input('cart_data'),
            'shippingMethod' => $request->input('shippingMethod'),
            'selectedAddress' => $request->input('selectedAddress'),
            'points_used' => $request->input('points_used'),
            'source' => $request->input('source'),
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized or invalid role'], 403);
        }

        $cart = json_decode($request->input('cart_data'), true);
        if (empty($cart)) {
            return response()->json(['error' => 'Keranjang kosong'], 400);
        }

        $shippingMethod = $request->input('shippingMethod');
        $addressId = $request->input('selectedAddress');
        if ($shippingMethod === 'courier' && !$addressId) {
            return response()->json(['error' => 'Silakan pilih alamat pengiriman'], 400);
        }

        foreach ($cart as $item) {
            $barang = Barang::where('kode_produk', $item['kode'])->first();
            if (!$barang || $barang->status_barang === 'sold out') {
                return response()->json(['error' => 'Barang ' . $item['nama'] . ' sudah terjual'], 400);
            }
        }

        $totalItemsPrice = array_sum(array_column($cart, 'harga'));
        $shippingCost = $shippingMethod === 'courier' && $totalItemsPrice < 1500000 ? 100000 : 0;
        $pointsUsed = (int) $request->input('points_used', 0);
        $pointsDiscount = $pointsUsed * 100;
        $totalPrice = $totalItemsPrice + $shippingCost - $pointsDiscount;

        // Generate transaction ID
        $currentMonthYear = Carbon::now()->format('y.m'); // '25.06'
        $lastTransaction = TransaksiPembelian::where('id_transaksi_beli', 'like', $currentMonthYear . '.%')
            ->orderBy('id_transaksi_beli', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastSequence = (int) substr($lastTransaction->id_transaksi_beli, 6);
            $sequence = $lastSequence + 1;
        } else {
            $sequence = 1;
        }
        $transactionId = $currentMonthYear . '.' . str_pad($sequence, 3, '0', STR_PAD_LEFT);

        \Log::info('Generated Transaction ID', [
            'transactionId' => $transactionId,
            'lastTransaction' => $lastTransaction ? $lastTransaction->toArray() : null,
            'currentMonthYear' => $currentMonthYear,
            'sequence' => $sequence,
        ]);

        $existingTransaction = TransaksiPembelian::where('id_transaksi_beli', $transactionId)->first();
        if ($existingTransaction) {
            throw new \Exception('Transaction ID already exists: ' . $transactionId);
        }

        // Gunakan transaksi database untuk memastikan konsistensi
        \DB::beginTransaction();

        $transaksi = TransaksiPembelian::create([
            'id_transaksi_beli' => $transactionId,
            'alamat_email' => $user->alamat_email,
            'total_harga' => $totalPrice,
            'metode_pengiriman' => $shippingMethod,
            'tanggal_pesan' => Carbon::now(),
            'status_pembelian' => 'menunggu pembayaran',
            'waktu_batas' => Carbon::now()->addMinute(),
        ]);

        if (!$transaksi) {
            throw new \Exception('Gagal membuat transaksi');
        }
        \Log::info('Transaction created', ['transaksi' => $transaksi->toArray()]);

        foreach ($cart as $item) {
            \Log::info('Creating detail pembelian', [
                'id_transaksi_beli' => $transactionId,
                'kode_produk' => $item['kode'],
            ]);
            DetailPembelian::create([
                'id_transaksi_beli' => $transactionId,
                'kode_produk' => $item['kode'],
                'bonus_poin' => 0,
            ]);
        }

        if ($pointsUsed > 0) {
            $pembeli = Pembeli::where('alamat_email', $user->alamat_email)->first();
            if (!$pembeli || $pembeli->total_poin < $pointsUsed) {
                \DB::rollBack();
                return response()->json(['error' => 'Poin tidak mencukupi'], 400);
            }
            $pembeli->total_poin -= $pointsUsed;
            $pembeli->save();
            \Log::info('Points deducted', ['pointsUsed' => $pointsUsed, 'pembeli' => $pembeli->toArray()]);
        }

        \DB::commit();

        $source = $request->input('source', 'unknown'); // Default to 'unknown' if not provided

        return response()->json([
            'id_transaksi_beli' => $transactionId,
            'source' => $source,
            'waktu_batas' => $transaksi->waktu_batas->toIso8601String(),
        ], 200);
    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('Checkout Error: ' . $e->getMessage(), [
            'stack' => $e->getTraceAsString(),
            'request' => $request->all(),
        ]);
        return response()->json(['error' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
    }
}

    public function confirm(Request $request, $transactionId)
    {
        try {
            \Log::info('Starting confirm', [
                'transactionId' => $transactionId,
                'user' => $request->user() ? $request->user()->toArray() : null,
            ]);

            $user = $request->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized or invalid role'], 403);
            }

            $transaksi = TransaksiPembelian::where('id_transaksi_beli', $transactionId)->first();
            if (!$transaksi) {
                return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
            }
            \Log::info('Transaction found', ['transaksi' => $transaksi->toArray()]);

            if ($transaksi->status_pembelian !== 'menunggu pembayaran') {
                return response()->json(['error' => 'Transaksi tidak dapat dikonfirmasi'], 400);
            }

            $file = $request->file('payment_proof');
            if (!$file) {
                return response()->json(['error' => 'Bukti pembayaran diperlukan'], 400);
            }
            $filePath = $file->store('payment_proofs', 'public');
            \Log::info('Payment proof uploaded', ['filePath' => $filePath]);

            // Gunakan transaksi database untuk memastikan konsistensi
            \DB::beginTransaction();

            $details = DetailPembelian::where('id_transaksi_beli', $transactionId)->get();
            $totalItemsPrice = 0;
            foreach ($details as $detail) {
                $barang = Barang::where('kode_produk', $detail->kode_produk)->first();
                if ($barang) {
                    $totalItemsPrice += $barang->harga;
                    $barang->status_barang = 'sold out';
                    $barang->save();
                }
            }
            \Log::info('Items processed', ['totalItemsPrice' => $totalItemsPrice]);

            $pointsEarned = floor($totalItemsPrice / 10000);
            if ($totalItemsPrice > 500000) {
                $pointsEarned += floor($pointsEarned * 0.2);
            }

            $pembeli = Pembeli::where('alamat_email', $transaksi->alamat_email)->first();
            $pembeli->total_poin += $pointsEarned;
            $pembeli->save();
            \Log::info('Points updated', ['pointsEarned' => $pointsEarned, 'pembeli' => $pembeli->toArray()]);

            // Update transaksi berdasarkan metode pengiriman
            $transaksi->bukti_pembayaran = $filePath;
            $transaksi->tanggal_lunas = Carbon::now();

            if ($transaksi->metode_pengiriman === 'selfPickup') {
                $transaksi->status_pembelian = 'Menunggu Verifikasi';
                $transaksi->tanggal_ambil = Carbon::now()->addDays(2);
            } else {
                $transaksi->status_pembelian = 'Menunggu Verifikasi';
                $transaksi->tanggal_ambil = null; // Pastikan tetap null untuk metode courier
            }

            $transaksi->save();
            \Log::info('Transaction updated', ['transaksi' => $transaksi->toArray()]);

            \DB::commit();
            return response()->json(['success' => 'Pembayaran berhasil dikonfirmasi'], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Confirm Error: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
            return response()->json(['error' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
        }
    }

    public function checkExpiredTransactions()
    {
        $expiredTransactions = TransaksiPembelian::where('status_pembelian', 'menunggu pembayaran')
            ->where('waktu_batas', '<', Carbon::now())
            ->get();

        foreach ($expiredTransactions as $transaksi) {
            $this->rollbackTransaction($transaksi->id_transaksi_beli);
        }
    }

    private function rollbackTransaction($transactionId)
    {
        $transaksi = TransaksiPembelian::where('id_transaksi_beli', $transactionId)->first();
        if (!$transaksi || $transaksi->status_pembelian !== 'menunggu pembayaran') {
            return;
        }

        // Hanya ubah status, jangan hapus data
        $transaksi->status_pembelian = 'dibatalkan';
        $transaksi->tanggal_lunas = null; // Pastikan tetap null
        $transaksi->tanggal_ambil = null; // Pastikan tetap null
        $transaksi->bukti_pembayaran = null; // Pastikan tetap null
        $transaksi->save();

        // Kembalikan poin yang digunakan (jika ada)
        $details = DetailPembelian::where('id_transaksi_beli', $transactionId)->get();
        $cart = [];
        foreach ($details as $detail) {
            $barang = Barang::where('kode_produk', $detail->kode_produk)->first();
            if ($barang) {
                $cart[] = [
                    'kode' => $barang->kode_produk,
                    'nama' => $barang->nama_produk,
                    'harga' => $barang->harga,
                    'gambar' => $barang->gambar
                ];
            }
        }

        $pembeli = Pembeli::where('alamat_email', $transaksi->alamat_email)->first();
        if ($pembeli) {
            $pointsUsed = (int) ($transaksi->total_harga - array_sum(array_column($cart, 'harga')) + 100000) / 100;
            $pembeli->total_poin += $pointsUsed;
            $pembeli->save();
        }
    }

    public function cancel(Request $request, $transactionId)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized or invalid role'], 403);
        }

        $transaksi = TransaksiPembelian::where('id_transaksi_beli', $transactionId)->first();
        if (!$transaksi) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }
        if ($transaksi->status_pembelian !== 'menunggu pembayaran') {
            return response()->json(['error' => 'Transaksi tidak dapat dibatalkan'], 400);
        }

        // Ubah status tanpa menghapus data
        $transaksi->status_pembelian = 'dibatalkan';
        $transaksi->tanggal_lunas = null; // Pastikan tetap null
        $transaksi->tanggal_ambil = null; // Pastikan tetap null
        $transaksi->bukti_pembayaran = null; // Pastikan tetap null
        $transaksi->save();

        // Kembalikan poin yang digunakan (jika ada)
        $details = DetailPembelian::where('id_transaksi_beli', $transactionId)->get();
        $cart = [];
        foreach ($details as $detail) {
            $barang = Barang::where('kode_produk', $detail->kode_produk)->first();
            if ($barang) {
                $cart[] = [
                    'kode' => $barang->kode_produk,
                    'nama' => $barang->nama_produk,
                    'harga' => $barang->harga,
                    'gambar' => $barang->gambar
                ];
            }
        }

        $pembeli = Pembeli::where('alamat_email', $transaksi->alamat_email)->first();
        if ($pembeli) {
            $pointsUsed = (int) ($transaksi->total_harga - array_sum(array_column($cart, 'harga')) + 100000) / 100;
            $pembeli->total_poin += $pointsUsed;
            $pembeli->save();
        }

        return response()->json(['success' => 'Transaksi berhasil dibatalkan'], 200);
    }

    public function buyNow(Request $request, $kode_produk)
    {
        $user = $request->user();
        if (!$user) {
            return redirect('/login')->with('error', 'Unauthenticated. Silakan login ulang.');
        }
        if ($user->role !== 'pembeli') {
            return redirect('/login')->with('error', 'Hanya pembeli yang dapat membeli. Silakan login ulang.');
        }

        $barang = Barang::where('kode_produk', $kode_produk)->first();
        if (!$barang) {
            return redirect('/home')->with('error', 'Barang tidak ditemukan.');
        }
        if ($barang->status === 'sold out') {
            return redirect('/home')->with('error', 'Barang sudah terjual.');
        }

        $cart = [
            [
                'kode' => $barang->kode_produk,
                'nama' => $barang->nama_produk,
                'harga' => $barang->harga,
                'gambar' => $barang->gambar
            ]
        ];

        $token = $request->user()->currentAccessToken()->token;
        return redirect()->route('checkout.buy', ['cart' => urlencode(json_encode($cart)), 'token' => urlencode($token)]);
    }

    public function index()
    {
        $transactions = TransaksiPembelian::with('pembeli')
            ->whereNotNull('bukti_pembayaran') // Filter bukti_pembayaran tidak null
            ->get()
            ->map(function ($transaction) {
                return [
                    'id_transaksi_beli' => $transaction->id_transaksi_beli,
                    'pembeli' => $transaction->pembeli->nama_pembeli,
                    'total_harga' => $transaction->total_harga,
                    'status_pembelian' => $transaction->status_pembelian,
                    'tanggal_pesan' => $transaction->tanggal_pesan->format('Y-m-d'),
                    'alamat_email' => $transaction->alamat_email, // Untuk keperluan validasi
                ];
            });

        return response()->json($transactions);
    }

    public function validateTransaction(Request $request, $id)
    {
        $transaction = TransaksiPembelian::find($id);
        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $isValid = $request->input('is_valid');
        $pembeli = $transaction->pembeli;

        if ($isValid) {
            // Jika valid, status dan poin tetap
            if($transaction->metode_pengiriman === 'selfPickup'){
                $transaction->status_pembelian = 'Siap Diambilkan';
            }else{
                $transaction->status_pembelian = 'Sedang disiapkan';
            }
             // Misalnya, status saat valid
            $transaction->tanggal_lunas = now();
            $transaction->save();

            return response()->json(['message' => 'Bukti pembayaran divalidasi'], 200);
        } else {
            // Jika tidak valid, ubah status barang, kurangi poin pembeli, dan ubah status transaksi
            $barang = Barang::whereHas('detail_pembelian', function ($query) use ($id) {
                $query->where('id_transaksi_beli', $id);
            })->first();

            if ($barang) {
                $barang->status_barang = ''; // Ubah status barang menjadi kosong
                $barang->save();
            }

            // Kurangi poin pembeli (asumsikan poin dikurangi berdasarkan total_harga)
            if ($pembeli && $pembeli->total_poin > 0) {
                $poinToDeduct = (int)($transaction->total_harga / 1000); // Contoh: 1 poin = 1000 rupiah
                $pembeli->total_poin -= $poinToDeduct;
                $pembeli->save();
            }

            // Ubah status transaksi menjadi "Ditolak"
            $transaction->status_pembelian = 'Ditolak';
            $transaction->tanggal_lunas = null;
            $transaction->save();

            return response()->json(['message' => 'Bukti pembayaran ditolak, status transaksi diubah'], 200);
        }
    }

    public function getCourierTasks()
    {
        try {
            // Ambil semua transaksi pengiriman kurir dengan eager loading
            $transactions = TransaksiPembelian::with(['pembeli.alamats'])
                ->where('metode_pengiriman', 'courier')
                ->whereNotIn('status_pembelian', ['Ditolak', 'Dibatalkan', 'Menunggu Pembayaran'])
                ->get();

            // Kelompokkan berdasarkan id_transaksi_beli dan gabungkan nama_barang
            $tasks = $transactions->map(function ($transaction) {
                // Ambil alamat utama dari pembeli
                $alamat = 'Alamat Tidak Diketahui';
                if ($transaction->pembeli) {
                    $alamatUtama = $transaction->pembeli->alamats->firstWhere('status_alamat', 'Utama');
                    $alamat = $alamatUtama ? $alamatUtama->nama_alamat ?? 'Alamat Tidak Diketahui' : 'Alamat Tidak Diketahui';
                }

                // Ambil semua detail_pembelian terkait
                $detailPembelians = $transaction->detail_pembelian;
                $namaBarangs = $detailPembelians->map(function ($detail) {
                    return $detail->barang->nama_produk ?? 'Tidak diketahui';
                })->unique()->join(', ');

                return [
                    'id_transaksi_beli' => $transaction->id_transaksi_beli,
                    'pembeli' => $transaction->pembeli ? ($transaction->pembeli->nama_pembeli ?? 'Nama Tidak Diketahui') : 'Nama Tidak Diketahui',
                    'alamat' => $alamat,
                    'nama_barang' => $namaBarangs ?: 'Tidak diketahui',
                    'total_harga' => $transaction->total_harga ?? 0,
                    'tanggal_lunas' => optional($transaction->tanggal_lunas)->toDateString(),
                    'status_pembelian' => $transaction->status_pembelian,
                ];
            })->unique('id_transaksi_beli')->values();

            Log::info('getCourierTasks executed successfully, records: ' . $tasks->count());
            return response()->json($tasks);
        } catch (\Exception $e) {
            Log::error('Error in getCourierTasks: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan server'], 500);
        }
    }

    public function completeDelivery($transactionId)
    {
        $transaction = TransaksiPembelian::find($transactionId);
        if (!$transaction || $transaction->metode_pengiriman != 'courier') {
            return response()->json(['error' => 'Transaksi tidak ditemukan atau bukan pengiriman kurir'], 404);
        }

        $transaction->status_pembelian = 'Selesai';
        $transaction->save();

        return response()->json(['success' => true]);
    }
}
