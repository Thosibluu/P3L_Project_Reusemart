<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
	protected $table = 'alamat';
	protected $primaryKey = 'id_alamat';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'id_alamat',
		'nama_alamat',
		'jenis_alamat',
		'status_alamat',
		'alamat_email',
	];

	public function pembeli()
	{
		return $this->belongsTo(Pembeli::class, 'alamat_email');
	}
}
