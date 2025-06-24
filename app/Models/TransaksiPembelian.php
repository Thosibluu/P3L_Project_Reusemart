<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransaksiPembelian
 * 
 * @property string $id_transaksi_beli
 * @property string $alamat_email
 * @property float $total_harga
 * @property Carbon $tanggal_pesan
 * @property Carbon|null $tanggal_lunas
 * @property Carbon|null $tanggal_ambil
 * @property string $status_pembelian
 * 
 * @property Pembeli $pembeli
 * @property DetailPembelian|null $detail_pembelian
 * @property Komisi|null $komisi
 * @property PjPembelian|null $pj_pembelian
 *
 * @package App\Models
 */
class TransaksiPembelian extends Model
{
	protected $table = 'transaksi_pembelian';
	protected $primaryKey = 'id_transaksi_beli';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'total_harga' => 'float',
		'tanggal_pesan' => 'datetime',
		'tanggal_lunas' => 'datetime',
		'tanggal_ambil' => 'datetime',
		'waktu_batas' => 'datetime'
	];

	protected $fillable = [
		'id_transaksi_beli',
		'alamat_email',
		'total_harga',
		'tanggal_pesan',
		'tanggal_lunas',
		'tanggal_ambil',
		'status_pembelian',
		'metode_pengiriman',
		'waktu_batas',
		'bukti_pembayaran'
	];

	public function pembeli()
	{
		return $this->belongsTo(Pembeli::class, 'alamat_email');
	}

	public function detail_pembelian()
	{
		return $this->hasMany(DetailPembelian::class, 'id_transaksi_beli');
	}

	public function komisi()
	{
		return $this->hasOne(Komisi::class, 'id_transaksi_beli');
	}

	public function pj_pembelian()
	{
		return $this->hasOne(PjPembelian::class, 'id_transaksi_beli');
	}
}
