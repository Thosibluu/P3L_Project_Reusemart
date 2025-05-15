<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Organisasi extends Model
{
	protected $table = 'organisasi';
	protected $primaryKey = 'id_organisasi';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'tanggal_tambah_organisasi_log' => 'datetime',
		'tanggal_ubah_organisasi_log' => 'datetime',
		'tanggal_hapus_organisasi_log' => 'datetime',
		'user_organisasi_log' => 'datetime'
	];

	protected $fillable = [
		'nama_organisasi',
		'alamat_organisasi',
		'nomor_telpon_organisasi',
		'password'
	];

	public function request_donasi()
	{
		return $this->hasOne(RequestDonasi::class, 'id_organisasi');
	}

	public function transaksi_donasi()
	{
		return $this->hasOne(TransaksiDonasi::class, 'id_organisasi');
	}
}
