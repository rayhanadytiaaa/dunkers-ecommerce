<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $fillable = ['nama'];

    public function ukurans()
    {
        return $this->belongsToMany(Ukuran::class, 'kategori_ukuran');
    }

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}

