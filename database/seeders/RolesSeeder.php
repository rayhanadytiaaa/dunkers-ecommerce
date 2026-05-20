<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Roles;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Roles::insert([
            ['name' => 'admin'],
            ['name' => 'pelanggan'],
        ]);
    }
}
