<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Merchandise
 * 
 * @property string $id_merchandise
 * @property string $nama_merchandise
 * @property int $poin_penukaran
 * 
 * @property TransaksiPenukaran|null $transaksi_penukaran
 *
 * @package App\Models
 */
class Merchandise extends Model
{
	protected $table = 'merchandise';
	protected $primaryKey = 'id_merchandise';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'poin_penukaran' => 'int'
	];

	protected $fillable = [
		'nama_merchandise',
		'poin_penukaran'
	];

	public function transaksi_penukaran()
	{
		return $this->hasOne(TransaksiPenukaran::class, 'id_merchandise');
	}
}
