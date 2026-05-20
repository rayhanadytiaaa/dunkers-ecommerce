<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $casts = [
        'tanggal' => 'date',
    ];
    
    protected $fillable = [
        'id',
        'user_id',
        'metode_pembayaran_id',
        'tanggal',
        'total',
        'status'
    ];

    public function detail()
    {
        return $this->hasMany(Detail_Transaksi::class, 'transaksi_id');
    }

    public function metode()
    {
        return $this->belongsTo(Metode_Pembayaran::class,'metode_pembayaran_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

}
