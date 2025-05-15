<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
	protected $table = 'pegawai';
	protected $primaryKey = 'id_pegawai';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'tanggal_tambah_pegawai_log' => 'datetime',
		'tanggal_lahir' => 'date',
		'tanggal_ubah_pegawai_log' => 'datetime',
		'tanggal_hapus_pegawai_log' => 'datetime',
		'user_pegawai_log' => 'datetime'
	];

	protected $fillable = [
		'id_role',
		'nama_pegawai',
		'alamat_pegawai',
		'no_telpon_pegawai',
		'tanggal_lahir',
		'tanggal_tambah_pegawai_log',
		'tanggal_ubah_pegawai_log',
		'tanggal_hapus_pegawai_log',
		'user_pegawai_log'
	];

	public function role()
	{
		return $this->belongsTo(Role::class, 'id_role');
	}

	public function komisi()
	{
		return $this->hasOne(Komisi::class, 'id_pegawai');
	}

	public function pj_pembelian()
	{
		return $this->hasOne(PjPembelian::class, 'id_pegawai');
	}
}
