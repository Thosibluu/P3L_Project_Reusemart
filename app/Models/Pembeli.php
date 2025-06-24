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


class Pembeli extends Authenticatable
{
	use HasApiTokens, Notifiable, HasFactory;
	protected $table = 'pembeli';
	protected $primaryKey = 'alamat_email';
	public $incrementing = false;
	public $timestamps = false;
	protected $keyType = 'string';

	protected $casts = [
		'total_poin' => 'int',
		'tanggal_tambah_pembeli_log' => 'datetime',
		'tanggal_ubah_pembeli_log' => 'datetime',
		'tanggal_hapus_pembeli_log' => 'datetime',
		'user_pembeli_log' => 'datetime'
	];

	protected $fillable = [
		'alamat_email',
		'password',
		'device_token',
		'nama_pembeli',
		'nomor_telepon_pembeli',
		'total_poin',
		'gambar'
	];

	public function alamats()
	{
		return $this->hasMany(Alamat::class, 'alamat_email');
	}

	public function transaksi_pembelians()
	{
		return $this->hasMany(TransaksiPembelian::class, 'alamat_email');
	}

	public function transaksi_penukaran()
	{
		return $this->hasOne(TransaksiPenukaran::class, 'id_user');
	}
}
