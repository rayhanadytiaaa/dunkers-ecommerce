<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User as ModelsUser;

class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsUser::create([
            'name' => 'User A',
            'email' => 'usera@gmail.com',
            'alamat' => 'Jl. Padang no.01',
            'nomor_hp' => '0811223345',
            'roles' => 'admin',
            'password' => bcrypt('12345678'),
            
        ]);

        ModelsUser::create([
            'name' => 'User B',
            'email' => 'userb@gmail.com',
            'alamat' => 'Jl. Padang no.02',
            'nomor_hp' => '0811223346',
            'roles' => 'admin',
            'password' => bcrypt('12345678'),
        ]);

        ModelsUser::create([
            'name' => 'User C',
            'email' => 'userc@gmail.com',
            'alamat' => 'Jl. Padang no.03',
            'nomor_hp' => '0811223347',
            'roles' => 'pelanggan',
            'password' => bcrypt('12345678'),
        ]);

        ModelsUser::create([
            'name' => 'User D',
            'email' => 'userd@gmail.com',
            'alamat' => 'Jl. Padang no.04',
            'nomor_hp' => '0811223348',
            'roles' => 'pelanggan',
            'password' => bcrypt('12345678'),
        ]);

        ModelsUser::create([
            'name' => 'User E',
            'email' => 'usere@gmail.com',
            'alamat' => 'Jl. Padang no.05',
            'nomor_hp' => '0811223349',
            'roles' => 'pelanggan',
            'password' => bcrypt('12345678'),
        ]);

        ModelsUser::create([
            'name' => 'User F',
            'email' => 'userf@gmail.com',
            'alamat' => 'Jl. Padang no.06',
            'nomor_hp' => '0811223340',
            'roles' => 'pelanggan',
            'password' => bcrypt('12345678'),
        ]);
    }
}
