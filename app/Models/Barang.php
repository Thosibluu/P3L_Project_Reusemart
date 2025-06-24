<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
	protected $table = 'barang';
	protected $primaryKey = 'kode_produk';
	public $incrementing = false;
	public $timestamps = false;
	protected $keyType = 'string';

	protected $casts = [
		'harga' => 'float',
		'tanggal_tambah_barang_log' => 'datetime',
		'tanggal_ubah_barang_log' => 'datetime',
		'tanggal_hapus_barang_log' => 'datetime',
		'user_barang_log' => 'datetime'
	];

	protected $fillable = [
		'id_kategori',
		'nama_produk',
		'status_perpanjang',
		'harga',
		'status_barang',
		'gambar',
		'deskripsi',
		'tanggal_tambah_barang_log',
		'tanggal_ubah_barang_log',
		'tanggal_hapus_barang_log',
		'user_barang_log'
	];

	public function kategori()
	{
		return $this->belongsTo(Kategori::class, 'id_kategori');
	}

	public function detail_pembelian()
	{
		return $this->hasOne(DetailPembelian::class, 'kode_produk');
	}

	public function detailPenitipan()
	{
		return $this->hasOne(DetailPenitipan::class, 'kode_produk');
	}
	
	public function request_donasi()
	{
		return $this->hasOne(RequestDonasi::class, 'kode_produk');
	}

	public function transaksi_donasi()
	{
		return $this->hasOne(TransaksiDonasi::class, 'kode_produk');
	}

	
}
