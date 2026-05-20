<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Produk;
use App\Models\Metode_Pembayaran;
use App\Models\Transaksi;
use App\Models\Detail_Transaksi;
use App\Models\ProdukUkuranStok;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request, Produk $produk)
    {
        $produk->load('kategori.ukurans', 'ukuranStoks');

        if ($produk->kategori && $produk->kategori->ukurans->count()) {
            $request->validate([
                'ukuran_id' => 'required|exists:ukuran,id',
                'qty' => 'required|min:1|integer'
            ]);
        } else {
            $request->validate([
                'qty' => 'required|min:1|integer'
            ]);
        }

        $ukuranId = $request->ukuran_id ?? null;

        if ($ukuranId && !$produk->kategori->ukurans->contains('id', (int) $ukuranId)) {
            return back()->with('error', 'Ukuran tidak valid untuk produk ini');
        }

        $existingCart = Cart::where('user_id', Auth::id())
            ->where('produk_id', $produk->id)
            ->where('ukuran_id', $ukuranId)
            ->first();

        $requestedQty = (int) $request->qty;
        $newQty = $requestedQty + (int) ($existingCart->qty ?? 0);

        if ($ukuranId) {
            $stokUkuran = ProdukUkuranStok::where('produk_id', $produk->id)
                ->where('ukuran_id', $ukuranId)
                ->value('stok');

            if ($stokUkuran === null) {
                $stokUkuran = (int) $produk->stok;
            }

            if ((int) $stokUkuran <= 0) {
                return back()->with('error', 'Stok untuk ukuran yang dipilih telah habis');
            }

            if ($newQty > (int) $stokUkuran) {
                return back()->with('error', 'Kuantitas melebihi stok ukuran yang tersedia (' . (int) $stokUkuran . ')');
            }
        } else {
            if ($produk->stok <= 0) {
                return back()->with('error', 'Stok produk telah habis');
            }

            if ($newQty > $produk->stok) {
                return back()->with('error', 'Kuantitas yang diminta melebihi stok yang tersedia');
            }
        }

        if ($existingCart) {
            $existingCart->update(['qty' => $newQty]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'produk_id' => $produk->id,
                'ukuran_id' => $ukuranId,
                'qty' => $requestedQty,
            ]);
        }

        return back()->with('success','Produk berhasil dimasukkan ke keranjang');
    }

    public function index()
    {
        $carts = Cart::with(['produk','ukuran'])
                    ->where('user_id', Auth::id())
                    ->get();

        return view('cart', compact('carts'));
    }

    public function checkout(Request $request)
    {
        $carts = Cart::where('user_id', Auth::id())->get();

        foreach ($carts as $cart) {
            if ($cart->ukuran_id) {
                ProdukUkuranStok::where('produk_id', $cart->produk_id)
                    ->where('ukuran_id', $cart->ukuran_id)
                    ->decrement('stok', $cart->qty);
            }
            $cart->produk->decrement('stok', $cart->qty);
        }

        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('produk')->with('success','Checkout berhasil! Terima kasih sudah berbelanja di Dunkers 🙌');
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'qty' => 'required|min:1|integer'
        ]);

        if ($cart->ukuran_id) {
            $stokUkuran = ProdukUkuranStok::where('produk_id', $cart->produk_id)
                ->where('ukuran_id', $cart->ukuran_id)
                ->value('stok');

            if ($stokUkuran === null) {
                $stokUkuran = (int) $cart->produk->stok;
            }

            if ($request->qty > (int) $stokUkuran) {
                return back()->with('error', 'Kuantitas melebihi stok ukuran yang tersedia (' . (int) $stokUkuran . ')');
            }
        } elseif ($request->qty > $cart->produk->stok) {
            return back()->with('error', 'Kuantitas melebihi stok yang tersedia (' . $cart->produk->stok . ')');
        }

        $cart->update(['qty' => $request->qty]);

        return back()->with('success', 'Kuantitas berhasil diperbarui');
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return back()->with('success','Produk dibatalkan');
    }

    public function checkoutPage()
    {
        $carts = Cart::where('user_id',Auth::id())->with('produk','ukuran')->get();
        $metodes = Metode_Pembayaran::all();

        return view('checkout', compact('carts','metodes'));
    }

    public function checkoutStore(Request $request)
    {
        $request->validate([
            'metode_pembayaran_id'=>'required|exists:metode_pembayaran,id'
        ]);

        $carts = Cart::where('user_id',Auth::id())->with('produk')->get();

        DB::transaction(function() use ($request,$carts){

            $total = $carts->sum(fn($c)=> $c->produk->harga * $c->qty);

            $trx = Transaksi::create([
                'user_id'=>Auth::id(),
                'metode_pembayaran_id'=>$request->metode_pembayaran_id,
                'total'=>$total,
                'status'=>'paid',
                'tanggal' => now()
            ]);

            foreach($carts as $cart){
                Detail_Transaksi::create([
                    'transaksi_id'=>$trx->id,
                    'produk_id'=>$cart->produk_id,
                    'ukuran_id'=>$cart->ukuran_id,
                    'jumlah'=>$cart->qty,
                    'harga'=>$cart->produk->harga
                ]);

                if ($cart->ukuran_id) {
                    ProdukUkuranStok::where('produk_id', $cart->produk_id)
                        ->where('ukuran_id', $cart->ukuran_id)
                        ->decrement('stok', $cart->qty);
                }

                $cart->produk->decrement('stok',$cart->qty);
            }

            Cart::where('user_id',Auth::id())->delete();
        });

        return redirect('/')->with('success','Checkout berhasil! Terima kasih sudah berbelanja di Dunkers 🙌');
    }

}

