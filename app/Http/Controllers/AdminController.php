<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\User;
use App\Models\Merek;
use App\Models\Kategori;
use App\Models\ProdukUkuranStok;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index(){

        return view('admin.index',[
        'totalProduk'=>Produk::count(),
        'totalTransaksi'=>Transaksi::count(),
        'totalUser'=>User::count(),
        ]);

    }

    public function kelolaProduk()
    {
        $produk = Produk::latest()->paginate(8);
        return view('admin.kelolaProduk', compact('produk'));
    }

    public function createProduk()
    {
        $kategori = Kategori::with('ukurans')->get();
        $merek = Merek::all();
    
        return view('admin.createProduk', compact('kategori','merek'));
    }

    public function storeProduk(Request $request)
    {

        $request->merge([
            'harga' => str_replace(['Rp', '.', ' '], '', $request->harga)
        ]);

        $data = $request->validate([
            'nama'        => 'required|string|max:150',
            'harga'       => 'required|numeric',
            'kategori_id' => 'required|integer|exists:kategori,id',
            'merek_id'    => 'required|integer|exists:merek,id',
            'stok'        => 'nullable|numeric|min:0',
            'ukuran_stok.*' => 'nullable|integer|min:0',
            'deskripsi'   => 'nullable|string|max:2000',

            'gambarproduk'  => 'nullable|file|image|mimes:png|max:2048',
            'gambarproduk1' => 'nullable|file|image|mimes:png,jpg,jpeg,avif|max:2048',
            'gambarproduk2' => 'nullable|file|image|mimes:png,jpg,jpeg,avif|max:2048',
            'gambarproduk3' => 'nullable|file|image|mimes:png,jpg,jpeg,avif|max:2048',
        ]);

        $kategoriTerpilih = Kategori::with('ukurans')->find($data['kategori_id']);
        $hasUkuran = $kategoriTerpilih && $kategoriTerpilih->ukurans->isNotEmpty();

        if ($hasUkuran) {
            $totalStok = 0;
            $ukuranStokInput = $request->input('ukuran_stok', []);

            foreach ($kategoriTerpilih->ukurans as $ukuran) {
                $stokUkuran = (int) ($ukuranStokInput[$ukuran->id] ?? 0);
                $totalStok += $stokUkuran;
            }

            $data['stok'] = $totalStok;
        } elseif ($request->stok === null || $request->stok === '') {
            return back()->withErrors([
                'stok' => 'Stok wajib diisi untuk kategori tanpa ukuran.',
            ])->withInput();
        }

        $folder = public_path('storage/img/produk');

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $fields = ['gambarproduk','gambarproduk1','gambarproduk2','gambarproduk3'];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
        
                $namaFile = $file->getClientOriginalName();
        
                $file->move($folder, $namaFile);
        
                $data[$field] = $namaFile;
            }
        }
        
        $produk = Produk::create($data);

        if ($hasUkuran) {
            $ukuranStokInput = $request->input('ukuran_stok', []);
            foreach ($kategoriTerpilih->ukurans as $ukuran) {
                ProdukUkuranStok::updateOrCreate(
                    [
                        'produk_id' => $produk->id,
                        'ukuran_id' => $ukuran->id,
                    ],
                    [
                        'stok' => (int) ($ukuranStokInput[$ukuran->id] ?? 0),
                    ]
                );
            }
        }

        return redirect()->route('admin.produk.index')
            ->with('success','Produk berhasil ditambah');
    }

    public function editProduk($id)
    {
        $produk = Produk::with('ukuranStoks')->findOrFail($id);
        $kategori = Kategori::with('ukurans')->get();
        $merek = Merek::all();

        return view('admin.editProduk', compact('produk','kategori','merek'));
    }

    public function updateProduk(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        // Bersihkan format Rupiah
        $request->merge([
            'harga' => str_replace(['Rp', '.', ' '], '', $request->harga)
        ]);

        $data = $request->validate([
            'nama'        => 'required|string|max:150',
            'harga'       => 'required|numeric',
            'kategori_id' => 'required|integer|exists:kategori,id',
            'merek_id'    => 'required|integer|exists:merek,id',
            'stok'        => 'nullable|numeric|min:0',
            'ukuran_stok.*' => 'nullable|integer|min:0',
            'deskripsi'   => 'nullable|string|max:2000',

            'gambarproduk'  => 'nullable|file|image|mimes:png|max:2048',
            'gambarproduk1' => 'nullable|file|image|mimes:png,jpg,jpeg,avif|max:2048',
            'gambarproduk2' => 'nullable|file|image|mimes:png,jpg,jpeg,avif|max:2048',
            'gambarproduk3' => 'nullable|file|image|mimes:png,jpg,jpeg,avif|max:2048',
        ]);

        $kategoriTerpilih = Kategori::with('ukurans')->find($data['kategori_id']);
        $hasUkuran = $kategoriTerpilih && $kategoriTerpilih->ukurans->isNotEmpty();

        if ($hasUkuran) {
            $totalStok = 0;
            $ukuranStokInput = $request->input('ukuran_stok', []);

            foreach ($kategoriTerpilih->ukurans as $ukuran) {
                $stokUkuran = (int) ($ukuranStokInput[$ukuran->id] ?? 0);
                $totalStok += $stokUkuran;
            }

            $data['stok'] = $totalStok;
        } elseif ($request->stok === null || $request->stok === '') {
            return back()->withErrors([
                'stok' => 'Stok wajib diisi untuk kategori tanpa ukuran.',
            ])->withInput();
        }

        $folder = public_path('storage/img/produk');

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $fields = ['gambarproduk','gambarproduk1','gambarproduk2','gambarproduk3'];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {

                // hapus gambar lama
                if ($produk->$field && file_exists($folder.'/'.$produk->$field)) {
                    unlink($folder.'/'.$produk->$field);
                }

                $file = $request->file($field);
                $namaFile = $file->getClientOriginalName();
                $file->move($folder, $namaFile);

                $data[$field] = $namaFile;
            }
        }

        $produk->update($data);

        if ($hasUkuran) {
            $ukuranStokInput = $request->input('ukuran_stok', []);
            $allowedUkuranIds = $kategoriTerpilih->ukurans->pluck('id')->all();

            // Hapus stok ukuran yang tidak relevan ketika kategori berubah.
            $produk->ukuranStoks()->whereNotIn('ukuran_id', $allowedUkuranIds)->delete();

            foreach ($kategoriTerpilih->ukurans as $ukuran) {
                ProdukUkuranStok::updateOrCreate(
                    [
                        'produk_id' => $produk->id,
                        'ukuran_id' => $ukuran->id,
                    ],
                    [
                        'stok' => (int) ($ukuranStokInput[$ukuran->id] ?? 0),
                    ]
                );
            }
        } else {
            // Kategori tanpa ukuran hanya memakai stok umum.
            $produk->ukuranStoks()->delete();
        }

        return redirect()->route('admin.produk.index')
            ->with('success','Produk berhasil diupdate');
    }

    public function destroyProduk($id)
    {
        Produk::destroy($id);

        return redirect()->route('admin.produk.index')
        ->with('success','Produk berhasil dihapus');
    }

    public function riwayat(Request $r)
    {
        $q = Transaksi::with(['user', 'detail.produk']);

        if ($r->from && $r->to) {
            $q->whereBetween('tanggal', [$r->from, $r->to]);
        }

        $transaksi = $q->latest()->paginate(8)->withQueryString();

        return view('admin.RiwayatTransaksi', compact('transaksi'));
    }

    public function riwayatPdf(Request $r)
    {
        $q = Transaksi::with(['user', 'detail.produk']);

        if ($r->from && $r->to) {
            $q->whereBetween('tanggal', [$r->from, $r->to]);
        }

        $transaksi = $q->latest()->get();

        $pdf = Pdf::loadView('admin.riwayat_pdf', compact('transaksi', 'r'));

        return $pdf->download('riwayat-transaksi.pdf');
    }

    public function users()
    {
        $adminUsers = User::with('role')
            ->where('role_id', 1)
            ->latest()
            ->paginate(8, ['*'], 'admin_page');

        $customerUsers = User::with('role')
            ->where('role_id', 2)
            ->latest()
            ->paginate(8, ['*'], 'customer_page');

        return view('admin.users', compact('adminUsers', 'customerUsers'));
    }

    public function createUser()
    {
        return view('admin.create_user');
    }

    public function storeUser(Request $r)
    {
        $data = $r->validate([
            'username' => ['required', 'string', 'max:60', Rule::unique('users', 'username')->whereNull('deleted_at')],
            'name' => 'required|string|max:100',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:191', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => 'required|min:6|max:100',
            'nomor_hp' => ['required', 'string', 'max:15', 'regex:/^08[0-9]{8,11}$/']
        ]);

        User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'nomor_hp' => $data['nomor_hp'],
            'role_id' => 1, // otomatis admin
        ]);

        return redirect()->route('admin.users')->with('success', 'Admin baru berhasil ditambahkan');
    }

    public function editUser(User $user)
    {
        return view('admin.edit_user', compact('user'));
    }

    public function updateUser(Request $r, User $user)
    {
        $data = $r->validate([
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:191',
                Rule::unique('users', 'email')->ignore($user->id)->whereNull('deleted_at'),
            ],
            'nomor_hp' => ['required', 'string', 'max:15', 'regex:/^08[0-9]{8,11}$/'],
            'role_id' => 'required|integer|exists:roles,id'
        ]);

        // Pelanggan tidak dapat dipromosikan menjadi admin melalui fitur ini.
        if ((int) $user->role_id === 2 && (int) $data['role_id'] === 1) {
            return back()
                ->withInput()
                ->with('error', 'Pelanggan tidak dapat diubah menjadi admin.');
        }

        // Admin yang sedang login tidak boleh mengubah status rolenya sendiri.
        if ((int) $user->id === (int) Auth::id() && (int) $user->role_id !== (int) $data['role_id']) {
            return back()
                ->withInput()
                ->with('error', 'Anda tidak dapat mengubah status akun Anda sendiri.');
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success','User berhasil diupdate');
    }

    public function destroyUser(User $user)
    {
        if ($user->id == Auth::id()) {
            return back()->with('error','Tidak bisa menghapus akun sendiri');
        }

        $archiveToken = 'deleted_' . $user->id . '_' . Str::lower(Str::random(6));
        $user->username = $archiveToken;
        $user->email = $archiveToken . '@deleted.local';
        $user->save();

        $user->delete();

        return redirect()->route('admin.users')->with('success','User berhasil dihapus');
    }


}
