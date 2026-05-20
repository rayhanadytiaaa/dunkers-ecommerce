<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukUkuranStok extends Model
{
    protected $table = 'produk_ukuran_stok';

    protected $fillable = [
        'produk_id',
        'ukuran_id',
        'stok',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function ukuran()
    {
        return $this->belongsTo(Ukuran::class, 'ukuran_id');
    }
}
