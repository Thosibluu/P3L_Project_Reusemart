<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWaktuBatasToTransaksiPembelianTable extends Migration
{
    public function up()
    {
        Schema::table('transaksi_pembelian', function (Blueprint $table) {
            $table->timestamp('waktu_batas')->nullable();
        });
    }

    public function down()
    {
        Schema::table('transaksi_pembelian', function (Blueprint $table) {
            $table->dropColumn('waktu_batas');
        });
    }
}