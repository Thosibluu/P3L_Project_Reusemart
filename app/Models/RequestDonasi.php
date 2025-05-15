<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RequestDonasi
 * 
 * @property string $id_organisasi
 * @property string $kode_produk
 * @property string $status_request
 * @property string $detail_request
 * 
 * @property Barang $barang
 * @property Organisasi $organisasi
 *
 * @package App\Models
 */
class RequestDonasi extends Model
{
	protected $table = 'request_donasi';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'id_organisasi',
		'kode_produk',
		'status_request',
		'detail_request'
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
