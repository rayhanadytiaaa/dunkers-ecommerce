<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Metode_Pembayaran;

class MetodePembayaranSeeder extends Seeder
{
    public function run(): void
    {
        Metode_Pembayaran::insert([
            ['nama' => 'Transfer Bank'],
            ['nama' => 'COD'],
        ]);
    }
}
