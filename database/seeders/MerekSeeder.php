<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Merek;

class MerekSeeder extends Seeder
{
    public function run(): void
    {
        Merek::insert([
            ['nama' => 'Nike'],
            ['nama' => 'Adidas'],
            ['nama' => 'Molten'],
            ['nama' => 'Lakers'],
            ['nama' => 'Spalding'],
        ]);
    }
}
