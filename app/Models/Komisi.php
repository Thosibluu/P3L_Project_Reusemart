<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Komisi
 * 
 * @property string $id_transaksi_beli
 * @property string $id_penitip
 * @property string $id_pegawai
 * @property float $jumlah_uang
 * @property float $komisi_hunter
 * @property float $komisi_perusahaan
 * @property float $bonus_penitip
 * 
 * @property Pegawai $pegawai
 * @property Penitip $penitip
 * @property TransaksiPembelian $transaksi_pembelian
 *
 * @package App\Models
 */
class Komisi extends Model
{
	protected $table = 'komisi';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'jumlah_uang' => 'float',
		'komisi_hunter' => 'float',
		'komisi_perusahaan' => 'float',
		'bonus_penitip' => 'float'
	];

	protected $fillable = [
		'id_transaksi_beli',
		'id_penitip',
		'id_pegawai',
		'jumlah_uang',
		'komisi_hunter',
		'komisi_perusahaan',
		'bonus_penitip'
	];

	public function pegawai()
	{
		return $this->belongsTo(Pegawai::class, 'id_pegawai');
	}

	public function penitip()
	{
		return $this->belongsTo(Penitip::class, 'id_penitip');
	}

	public function transaksi_pembelian()
	{
		return $this->belongsTo(TransaksiPembelian::class, 'id_transaksi_beli');
	}
}
