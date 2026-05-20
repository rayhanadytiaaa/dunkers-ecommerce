<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merek extends Model
{
    protected $table = 'merek';
    protected $fillable = [
        'id',
        'nama',
    ];

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}
