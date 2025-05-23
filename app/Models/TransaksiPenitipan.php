<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class TransaksiPenitipan extends Model
{
	protected $table = 'transaksi_penitipan';
	protected $primaryKey = 'id_transaksi_titip';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'tanggal_penitipan' => 'datetime',
		'batas_penitipan' => 'datetime',
		'batas_pengembalian' => 'datetime'
	];

	protected $fillable = [
		'id_penitip',
		'tanggal_penitipan',
		'batas_penitipan',
		'batas_pengembalian'
	];

	public function penitip()
	{
		return $this->belongsTo(Penitip::class, 'id_penitip');
	}

	public function detailPenitipan()
	{
		return $this->hasMany(DetailPenitipan::class, 'id_transaksi_titip');
	}
}
