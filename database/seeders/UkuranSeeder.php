<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ukuran;

class UkuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ukuran::insert([
            ['nama' => 'S'],
            ['nama' => 'M'],
            ['nama' => 'L'],
            ['nama' => 'XL'],
            ['nama' => '39'],
            ['nama' => '40'],
            ['nama' => '41'],
            ['nama' => '42'],
            ['nama' => '43'],
        ]);        
    }
}
