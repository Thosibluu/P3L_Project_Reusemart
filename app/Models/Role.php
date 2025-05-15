<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * 
 * @property string $id_role
 * @property string $nama_role
 * 
 * @property Collection|Pegawai[] $pegawais
 *
 * @package App\Models
 */
class Role extends Model
{
	protected $table = 'role';
	protected $primaryKey = 'id_role';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'nama_role'
	];

	public function pegawais()
	{
		return $this->hasMany(Pegawai::class, 'id_role');
	}
}
