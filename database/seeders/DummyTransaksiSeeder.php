<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\Detail_Transaksi;
use Carbon\Carbon;

class DummyTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat transaksi dummy untuk user ID 1 (Erlan)
        $transaksi = Transaksi::create([
            'id' => 8,
            'user_id' => 1,
            'metode_pembayaran_id' => 1,
            'tanggal' => Carbon::now()->subDays(2),
            'total' => 150000,
            'status' => 'completed'
        ]);

        // Tambahkan detail transaksi
        Detail_Transaksi::create([
            'transaksi_id' => 8,
            'produk_id' => 'P001',
            'qty' => 1,
            'harga' => 150000
        ]);

        // Transaksi kedua
        $transaksi2 = Transaksi::create([
            'id' => 9,
            'user_id' => 1,
            'metode_pembayaran_id' => 2,
            'tanggal' => Carbon::now()->subDays(5),
            'total' => 200000,
            'status' => 'paid'
        ]);

        Detail_Transaksi::create([
            'transaksi_id' => 9,
            'produk_id' => 'P002',
            'qty' => 2,
            'harga' => 100000
        ]);
    }
}
