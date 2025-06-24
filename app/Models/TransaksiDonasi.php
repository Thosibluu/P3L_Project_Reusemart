<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransaksiDonasi
 * 
 * @property string $kode_produk
 * @property string $id_organisasi
 * @property Carbon $tanggal_donasi
 * @property string $nama_penerima
 * @property Carbon $tanggal_cetak
 * 
 * @property Barang $barang
 * @property Organisasi $organisasi
 *
 * @package App\Models
 */
class TransaksiDonasi extends Model
{
	protected $table = 'transaksi_donasi';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'tanggal_donasi' => 'datetime',
		'tanggal_cetak' => 'datetime'
	];

	protected $fillable = [
		'kode_produk',
		'id_organisasi',
		'tanggal_donasi',
		'nama_penerima',
		'tanggal_cetak',
		'hunter_related'
	];

	public function barang()
	{
		return $this->belongsTo(Barang::class, 'kode_produk');
	}

	public function organisasi()
	{
		return $this->belongsTo(Organisasi::class, 'id_organisasi');
	}
}
