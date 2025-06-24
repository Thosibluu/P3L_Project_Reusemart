<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens; // Tambahkan ini
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penitip extends Authenticatable
{
	use HasApiTokens, Notifiable, HasFactory;
	protected $table = 'penitip';
	protected $primaryKey = 'id_penitip';
	public $incrementing = false;
	public $timestamps = false;
	protected $keyType = 'string';

	protected $casts = [
		'total_poin' => 'int',
		'tanggal_tambah_penitip_log' => 'datetime',
		'tanggal_ubah_penitip_log' => 'datetime',
		'tanggal_hapus_penitip_log' => 'datetime',
		'user_penitip_log' => 'datetime'
	];

	protected $fillable = [
		'nama_penitip',
		'saldo',
		'alamat_penitip',
		'nomor_telpon_penitip',
		'password',
		'device_token',
		'total_poin',
		'tanggal_tambah_penitip_log',
		'tanggal_ubah_penitip_log',
		'tanggal_hapus_penitip_log',
		'user_penitip_log'
	];

	public function komisi()
	{
		return $this->hasOne(Komisi::class, 'id_penitip');
	}

	public function transaksiPenitipans()
	{
		return $this->hasMany(TransaksiPenitipan::class, 'id_penitip');
	}
}
