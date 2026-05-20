<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Ukuran;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Insert kategori
        Kategori::insert([
            ['nama' => 'Sepatu'],
            ['nama' => 'Baju'],
            ['nama' => 'Aksesoris'],
            ['nama' => 'Celana'],
            ['nama' => 'Jaket'],
        ]);

        // Ambil kategori
        $sepatu  = Kategori::where('nama', 'Sepatu')->first();
        $baju    = Kategori::where('nama', 'Baju')->first();
        $celana  = Kategori::where('nama', 'Celana')->first();
        $jaket   = Kategori::where('nama', 'Jaket')->first();

        // Ambil ukuran berdasarkan nama (AMAN)
        $sizeS  = Ukuran::whereIn('nama', ['S','M','L','XL'])->pluck('id');
        $size39 = Ukuran::whereIn('nama', ['39','40','41','42','43'])->pluck('id');

        // Sync ukuran
        $sepatu->ukurans()->sync($size39);
        $baju->ukurans()->sync($sizeS);
        $celana->ukurans()->sync($sizeS);
        $jaket->ukurans()->sync($sizeS);
        // Aksesoris → tidak sync (tidak punya ukuran)
    }
}
