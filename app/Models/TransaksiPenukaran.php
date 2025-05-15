<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransaksiPenukaran
 * 
 * @property string $id_user
 * @property string $id_merchandise
 * @property Carbon $tanggal_penukaran
 * @property Carbon $tanggal_claim
 * @property string $status_penukaran
 * 
 * @property Merchandise $merchandise
 * @property Pembeli $pembeli
 *
 * @package App\Models
 */
class TransaksiPenukaran extends Model
{
	protected $table = 'transaksi_penukaran';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'tanggal_penukaran' => 'datetime',
		'tanggal_claim' => 'datetime'
	];

	protected $fillable = [
		'id_user',
		'id_merchandise',
		'tanggal_penukaran',
		'tanggal_claim',
		'status_penukaran'
	];

	public function merchandise()
	{
		return $this->belongsTo(Merchandise::class, 'id_merchandise');
	}

	public function pembeli()
	{
		return $this->belongsTo(Pembeli::class, 'id_user');
	}
}
