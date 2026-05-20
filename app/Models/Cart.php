<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id','produk_id','ukuran_id','qty'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function ukuran()
    {
        return $this->belongsTo(Ukuran::class);
    }
}

