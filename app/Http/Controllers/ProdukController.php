<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\User;
use App\Models\Detail_Transaksi;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'kategori' => ['nullable', 'integer', 'exists:kategori,id'],
        ]);

        $query = Produk::with('kategori')->orderBy('id', 'desc');

        // SEARCH
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // FILTER KATEGORI
        if ($request->filled('kategori')) {
            $query->where('kategori_id', (int) $request->kategori);
        }

        // PAGINATION 8 PRODUK PER HALAMAN
        $produkPage = $query->paginate(8)->withQueryString();

        return view('produk', compact('produkPage'));
    }


private function getUserVector(int $userId): array
{
    return Detail_Transaksi::join('transaksi', 'transaksi.id', '=', 'detail_transaksi.transaksi_id')
        ->join('produk', 'produk.id', '=', 'detail_transaksi.produk_id')
        ->where('transaksi.user_id', $userId)
        ->select(
            'produk.kategori_id',
            DB::raw('SUM(detail_transaksi.jumlah) as total')
        )
        ->groupBy('produk.kategori_id')
        ->pluck('total', 'kategori_id')
        ->toArray();
}
    /**
     * Cosine similarity antar user (murni)
     */
    private function cosineSimilarity(array $A, array $B): float
    {
        if (empty($A) || empty($B)) return 0;

        $dotProduct = 0;
        foreach ($A as $key => $value) {
            if (isset($B[$key])) {
                $dotProduct += $value * $B[$key];
            }
        }

        if ($dotProduct == 0) return 0;

        $normA = sqrt(array_sum(array_map(fn ($v) => $v ** 2, $A)));
        $normB = sqrt(array_sum(array_map(fn ($v) => $v ** 2, $B)));

        if ($normA == 0 || $normB == 0) return 0;

        return $dotProduct / ($normA * $normB);
    }

    private function getCFRecommendations(int $userId, int $limit = 4)
    {
        $user = Auth::user();

        if (!$user) return collect();

        if ($user->role_id == 1) return collect();

        $targetVector = $this->getUserVector($userId);
        if (count($targetVector) < 1) return collect();

        $users = User::where('id', '!=', $userId)
            ->where('role_id', '!=', 1)
            ->whereHas('transaksi')
            ->pluck('id');

        $similarities = [];

        foreach ($users as $otherUserId) {
            $otherVector = $this->getUserVector($otherUserId);
            if (empty($otherVector)) continue;

            $sim = $this->cosineSimilarity($targetVector, $otherVector);

            if ($sim >= 0.5) {
                $similarities[$otherUserId] = $sim;
            }
        }

        if (empty($similarities)) return collect();

        arsort($similarities);

        // Untuk setiap kategori dengan similarity, kumpulkan produk dari user lain
        // yang belum dibeli oleh current user
        $recommendedProducts = [];

        foreach ($similarities as $otherUserId => $sim) {
            $otherVector = $this->getUserVector($otherUserId);
            if (empty($otherVector)) continue;

            // Kategori yang mirip antara current user dan other user
            $commonCategories = array_intersect_key($targetVector, $otherVector);

            // Dari kategori yang mirip, ambil produk yang dibeli other user tapi belum dibeli current user
            foreach ($commonCategories as $kategoriId => $qty) {
                // Produk yang dibeli other user di kategori ini
                $otherUserProduksInCategory = Produk::where('kategori_id', $kategoriId)
                    ->whereIn('id', function ($query) use ($otherUserId) {
                        $query->select('produk_id')
                            ->from('detail_transaksi')
                            ->whereIn('transaksi_id', function ($q) use ($otherUserId) {
                                $q->select('id')->from('transaksi')->where('user_id', $otherUserId);
                            });
                    })
                    ->get();

                // Produk yang sudah dibeli current user
                $userProdukIds = Detail_Transaksi::join('transaksi', 'transaksi.id', '=', 'detail_transaksi.transaksi_id')
                    ->where('transaksi.user_id', $userId)
                    ->pluck('produk_id')
                    ->toArray();

                // Tambahkan produk dari other user yang belum dibeli current user
                foreach ($otherUserProduksInCategory as $prod) {
                    if (!in_array($prod->id, $userProdukIds)) {
                        if (!isset($recommendedProducts[$prod->id])) {
                            $recommendedProducts[$prod->id] = [
                                'product' => $prod,
                                'score' => $sim * $qty,
                            ];
                        } else {
                            // Jika sudah ada dari user lain, tambah scorenya
                            $recommendedProducts[$prod->id]['score'] += $sim * $qty;
                        }
                    }
                }
            }
        }

        if (empty($recommendedProducts)) return collect();


        usort($recommendedProducts, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });


        $rekomendasi = collect(array_slice($recommendedProducts, 0, $limit))
            ->pluck('product')
            ->values();

        return $rekomendasi;
    }

    /**
     * Menghasilkan detail similarity untuk debugging.
     *
     * @param int $userId
     * @return array
     */
    private function getSimilarityDebug(int $userId): array
{
    $targetVector = $this->getUserVector($userId);
    $users = User::where('id', '!=', $userId)
        ->where('role_id', '!=', 1)
        ->whereHas('transaksi')
        ->pluck('id');

    $details = [];
    $allKategoriIds = array_keys($targetVector);
    $userIdsWithVectors = [];
    $otherUsersProduk = []; // store produk per other user per kategori

    foreach ($users as $otherUserId) {
        $otherVector = $this->getUserVector($otherUserId);
        if (empty($otherVector)) continue;

        $sim = $this->cosineSimilarity($targetVector, $otherVector);
        $details[$otherUserId] = [
            'sim' => $sim,
            'vector' => $otherVector,
        ];

        $allKategoriIds = array_merge($allKategoriIds, array_keys($otherVector));
        $userIdsWithVectors[] = $otherUserId;

        // fetch produk yang dibeli user lain per kategori
        $otherUserProdukIds = Detail_Transaksi::join('transaksi', 'transaksi.id', '=', 'detail_transaksi.transaksi_id')
            ->where('transaksi.user_id', $otherUserId)
            ->pluck('produk_id')
            ->toArray();

        $otherUsersProduk[$otherUserId] = [];
        if (!empty($otherUserProdukIds)) {
            $produksData = Produk::whereIn('id', array_unique($otherUserProdukIds))
                ->select('id', 'nama', 'kategori_id')
                ->get();
            
            foreach ($produksData as $p) {
                if (!isset($otherUsersProduk[$otherUserId][$p->kategori_id])) {
                    $otherUsersProduk[$otherUserId][$p->kategori_id] = [];
                }
                $otherUsersProduk[$otherUserId][$p->kategori_id][] = $p->nama;
            }
        }
    }


    $categoryNames = Kategori::whereIn('id', array_unique($allKategoriIds))
        ->pluck('nama', 'id')
        ->toArray();


    $userProdukIds = Detail_Transaksi::join('transaksi', 'transaksi.id', '=', 'detail_transaksi.transaksi_id')
        ->where('transaksi.user_id', $userId)
        ->pluck('produk_id')
        ->toArray();
    
    $produkPerKategori = [];
    if (!empty($userProdukIds)) {
        $produksData = Produk::whereIn('id', array_unique($userProdukIds))
            ->select('id', 'nama', 'kategori_id')
            ->get();
        
        foreach ($produksData as $p) {
            if (!isset($produkPerKategori[$p->kategori_id])) {
                $produkPerKategori[$p->kategori_id] = [];
            }
            $produkPerKategori[$p->kategori_id][] = $p->nama;
        }
    }


    $userNames = [];
    if (!empty($userIdsWithVectors)) {
        $userNames = User::whereIn('id', array_unique($userIdsWithVectors))
            ->pluck('name', 'id')
            ->toArray();
    }

    return [
        'target'              => $targetVector,
        'others'              => $details,
        'categoryNames'       => $categoryNames,
        'userNames'           => $userNames,
        'produkPerKategori'   => $produkPerKategori,
        'otherUsersProduk'    => $otherUsersProduk,
    ];
}

    // ================== SHOW DETAIL + CF ==================

    public function show($id)
    {
        $user = Auth::user();
        $produk = Produk::with('kategori.ukurans', 'merek', 'ukuranStoks')->findOrFail($id);

        $rekomendasi = collect();
        $guest = true;
        $isAdmin = false;
        $userTransaksi = collect();
        $terjual = 0;
        $similarityDebug = null;
        $stokPerUkuran = collect();

        if ($produk->kategori && $produk->kategori->ukurans->isNotEmpty()) {
            $hasSizeStockRows = $produk->ukuranStoks->isNotEmpty();
            $stokPerUkuran = $produk->kategori->ukurans->mapWithKeys(function ($ukuran) use ($produk) {
                $stokData = $produk->ukuranStoks->firstWhere('ukuran_id', $ukuran->id);
                $stok = $stokData ? (int) $stokData->stok : 0;
                return [$ukuran->id => $stok];
            });

            if (!$hasSizeStockRows) {
                $stokPerUkuran = $produk->kategori->ukurans->mapWithKeys(function ($ukuran) use ($produk) {
                    return [$ukuran->id => (int) $produk->stok];
                });
            }
        }

        if (Auth::check()) {
            $guest = false;
            $user = Auth::user();

            if ($user->role_id == 1) {
                $isAdmin = true;
            } else {
                $userId = $user->id;
                $rekomendasi = $this->getCFRecommendations($userId, 4);

                // data transaksi pengguna untuk produk ini
                $userTransaksi = Detail_Transaksi::with('transaksi')
                    ->where('produk_id', $id)
                    ->whereHas('transaksi', fn($q) => $q->where('user_id', $userId))
                    ->get();

                // debug similarity
                $similarityDebug = $this->getSimilarityDebug($userId);
            }
        }

        // total produk terjual oleh semua user
        $terjual = Detail_Transaksi::where('produk_id', $id)->sum('jumlah');

        return view('detail-produk', compact(
            'produk', 'rekomendasi', 'guest', 'isAdmin',
            'userTransaksi', 'terjual', 'similarityDebug', 'stokPerUkuran'
        ));
    }

}
