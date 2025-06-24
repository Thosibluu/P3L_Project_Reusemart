<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBuktiPembayaranToTransaksiPembelianTable extends Migration
{
    public function up()
    {
        Schema::table('transaksi_pembelian', function (Blueprint $table) {
            $table->string('bukti_pembayaran')->nullable(); // Kolom untuk menyimpan path file, nullable jika belum ada bukti
        });
    }

    public function down()
    {
        Schema::table('transaksi_pembelian', function (Blueprint $table) {
            $table->dropColumn('bukti_pembayaran');
        });
    }

    
}
