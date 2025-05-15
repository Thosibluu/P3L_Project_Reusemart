<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    protected $table = 'komentar';
    protected $primaryKey = null; // No primary key, composite key implied by kode_produk and alamat_email
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_produk',
        'alamat_email',
        'komentar'
    ];

    // Relationship with Pembeli
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'alamat_email', 'alamat_email');
    }

    // Relationship with Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_produk', 'kode_produk');
    }
}