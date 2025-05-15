<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DetailPenitipan
 * 
 * @property string $kode_produk
 * @property string $id_transaksi_titip
 * 
 * @property Barang $barang
 * @property TransaksiPenitipan $transaksi_penitipan
 *
 * @package App\Models
 */
class DetailPenitipan extends Model
{
	protected $table = 'detail_penitipan';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'kode_produk',
		'id_transaksi_titip'
	];

	public function barang()
	{
		return $this->belongsTo(Barang::class, 'kode_produk');
	}

	public function transaksi_penitipan()
	{
		return $this->belongsTo(TransaksiPenitipan::class, 'id_transaksi_titip');
	}
}
