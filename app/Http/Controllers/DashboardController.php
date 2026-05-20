<?php

namespace App\Http\Controllers;

use App\Models\Detail_Transaksi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $produkTerlaris = Detail_Transaksi::select(
                'produk_id',
                DB::raw('SUM(jumlah) as total_terjual')
            )
            ->groupBy('produk_id')
            ->orderByDesc('total_terjual')
            ->with('produk')
            ->limit(4)
            ->get();

        return view('dashboard', compact('produkTerlaris'));
    }
}
