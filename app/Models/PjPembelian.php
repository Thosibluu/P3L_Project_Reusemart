<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PjPembelian
 * 
 * @property string $id_pegawai
 * @property string $id_transaksi_beli
 * @property Carbon|null $tanggal_proses
 * 
 * @property Pegawai $pegawai
 * @property TransaksiPembelian $transaksi_pembelian
 *
 * @package App\Models
 */
class PjPembelian extends Model
{
	protected $table = 'pj_pembelian';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'tanggal_proses' => 'datetime'
	];

	protected $fillable = [
		'id_pegawai',
		'id_transaksi_beli',
		'tanggal_proses'
	];

	public function pegawai()
	{
		return $this->belongsTo(Pegawai::class, 'id_pegawai');
	}

	public function transaksi_pembelian()
	{
		return $this->belongsTo(TransaksiPembelian::class, 'id_transaksi_beli');
	}
}
