<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;

    protected $table = 'produk';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'nama',
        'kategori_id',
        'merek_id',
        'deskripsi',
        'gambarproduk',
        'gambarproduk1',
        'gambarproduk2',
        'gambarproduk3',
        'harga',
        'stok',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function merek()
    {
        return $this->belongsTo(Merek::class);
    }

    public function ukuranStoks()
    {
        return $this->hasMany(ProdukUkuranStok::class, 'produk_id');
    }
}

