<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DetailPembelian
 * 
 * @property string $kode_produk
 * @property string $id_transaksi_beli
 * @property int $bonus_poin
 * 
 * @property Barang $barang
 * @property TransaksiPembelian $transaksi_pembelian
 *
 * @package App\Models
 */
class DetailPembelian extends Model
{
	protected $table = 'detail_pembelian';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'bonus_poin' => 'int'
	];

	protected $fillable = [
		'kode_produk',
		'id_transaksi_beli',
		'bonus_poin'
	];

	public function barang()
	{
		return $this->belongsTo(Barang::class, 'kode_produk');
	}

	public function transaksi_pembelian()
	{
		return $this->belongsTo(TransaksiPembelian::class, 'id_transaksi_beli');
	}
}
