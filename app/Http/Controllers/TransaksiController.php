<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function riwayat()
    {
        $transaksis = Transaksi::where('user_id', Auth::user()->id)
            ->with(['detail.produk', 'metode'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
        
        return view('riwayat-belanja', compact('transaksis'));
    }

    public function detail($id)
    {
        $transaksi = Transaksi::with(['detail.produk', 'metode', 'user'])
            ->findOrFail($id);
        
        // Pastikan user hanya bisa melihat transaksi mereka sendiri
        if ($transaksi->user_id !== Auth::user()->id) {
            abort(403);
        }
        
        return view('detail-transaksi', compact('transaksi'));
    }
}
