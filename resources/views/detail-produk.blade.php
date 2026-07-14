<x-app-layout>
    <div class="grid grid-cols-2 mt-10">

        {{-- ================= SISI KIRI ================= --}}
        <div class="flex justify-center w-[500px] ml-10"> 
    
            <div class="w-40 mx-4">
                <img src="{{ asset('img/produk/' . $produk->gambarproduk1) }}" 
                     alt="{{ $produk->nama }}" 
                     class="w-32 rounded-2xl shadow-gray-500/50 shadow-xl">
    
                <img src="{{ asset('img/produk/' . $produk->gambarproduk2) }}" 
                     alt="{{ $produk->nama }}" 
                     class="w-32 rounded-2xl shadow-gray-500/50 shadow-xl my-2">
    
                <img src="{{ asset('img/produk/' . $produk->gambarproduk3) }}" 
                     alt="{{ $produk->nama }}" 
                     class="w-32 rounded-2xl shadow-gray-500/50 shadow-xl my-2">    
            </div>
    
            <div class="max-w-lg mx-auto w-full">
                <div class="relative max-w-lg mx-auto overflow-hidden rounded-2xl shadow-gray-500/50 shadow-xl">
    
                    <div id="slider" class="flex transition-transform duration-700 ease-in-out">
    
                        <div class="min-w-full">
                            <img src="{{ asset('img/produk/' . $produk->gambarproduk1) }}" 
                                 class="w-full object-cover " 
                                 alt="{{ $produk->nama }}">
                        </div>
    
                        <div class="min-w-full">
                            <img src="{{ asset('img/produk/' . $produk->gambarproduk2) }}" 
                                 class="w-full object-cover" 
                                 alt="{{ $produk->nama }}">
                        </div>
    
                        <div class="min-w-full">
                            <img src="{{ asset('img/produk/' . $produk->gambarproduk3) }}" 
                                 class="w-full object-cover" 
                                 alt="{{ $produk->nama }}">
                        </div>
    
                    </div>
    
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                        <div class="dot w-3 h-3 rounded-full bg-white/50 cursor-pointer"></div>
                        <div class="dot w-3 h-3 rounded-full bg-white/50 cursor-pointer"></div>
                        <div class="dot w-3 h-3 rounded-full bg-white/50 cursor-pointer"></div>
                    </div>
    
                </div>
            </div>
        </div>
    
        {{-- ================= SISI KANAN ================= --}}
        <div class="text-black dark:text-white mr-10">
    
            <h2 class="text-3xl font-extrabold mb-1">
                {{ $produk->nama }}
            </h2>
    
            <h3 class="text-2xl font-extrabold mb-1">
                Rp {{ number_format($produk->harga,0,',','.') }}
            </h3>
    
            <p class="text-black dark:text-gray-300">{{ $produk->deskripsi }}</p>
    
            <br><br>
    
            {{-- ================= UKURAN ================= --}}
            <div class="text-md text-black dark:text-gray-300 mb-2">
                <span class="mr-4">
                    Kategori: 
                    <span class="text-orange-600 dark:text-orange-400 font-semibold">
                        {{ $produk->kategori?->nama ?? '-' }}
                    </span>
                </span>
            
                <span class="mr-4">
                    Merek: 
                    <span class="text-orange-600 dark:text-orange-400 font-semibold">
                        {{ $produk->merek?->nama ?? '-' }}
                    </span>
                </span>

                <span>
                    Stok yang tersedia: 
                    <span class="text-orange-600 dark:text-orange-400 font-semibold">
                        {{ $produk->stok }}
                    </span>
                </span>

                <span>
                    Terjual: 
                    <span class="text-orange-600 dark:text-orange-400 font-semibold">
                        {{ $terjual ?? 0 }}
                    </span>
                </span>
            </div>

            @php
                $isProdukDenganUkuran = $produk->kategori && $produk->kategori->ukurans->count();
                $stokUkuranMaks = $stokPerUkuran->max();
                $isStokHabis = $isProdukDenganUkuran ? (($stokUkuranMaks ?? 0) <= 0) : ($produk->stok <= 0);
            @endphp

            @if($isStokHabis)
                <div class="bg-red-600 text-white p-3 rounded-lg mt-4 font-semibold text-center">
                    ⚠️ Stok produk telah habis
                </div>
            @endif

            <form action="{{ route('cart.add', $produk->id) }}" method="POST" @if($isStokHabis) onsubmit="return false;" @endif>
                @csrf
                
                <div class=" grid grid-cols-2">
                    @auth
                        @if(auth()->user()->role_id == '1')
                            {{-- Kosong --}}
                            @elseif (auth()->user()->role_id == '2')
                            <div>
                                @if ($produk->kategori && $produk->kategori->ukurans->count())
                                    <h3 class="text-2xl font-extrabold mb-3">Ukuran</h3>
                                
                                    <div class="flex gap-3 flex-wrap">
                                        @foreach ($produk->kategori->ukurans as $ukuran)
                                            @php $stokUkuran = (int) ($stokPerUkuran[$ukuran->id] ?? 0); @endphp
                                            <label class="cursor-pointer">
                                                <input type="radio" 
                                                    name="ukuran_id" 
                                                    value="{{ $ukuran->id }}" 
                                                    data-stock="{{ $stokUkuran }}"
                                                    class="hidden peer ukuran-radio" required @disabled($stokUkuran <= 0)>
                                
                                                <span class="px-4 py-2 rounded-lg border 
                                                    dark:peer-checked:bg-[#E67E22]
                                                    dark:peer-checked:text-white
                                                    peer-checked:bg-gray-600
                                                    peer-checked:text-white {{ $stokUkuran <= 0 ? 'opacity-40 cursor-not-allowed' : '' }}">
                                                    {{ $ukuran->nama }} ({{ $stokUkuran }})
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-2xl ml-4 font-extrabold">Jumlah</h3>
                                <div class="flex items-center ml-4 gap-3">
                                
                                    <input type="number" id="qty_input" name="qty" value="1" min="1" class=" w-20 text-black rounded" max="{{ $produk->stok }}" @if($isStokHabis) disabled @endif>
                                    <button type="submit" 
                                        @if($isStokHabis) 
                                            disabled 
                                            class=" bg-gray-400 cursor-not-allowed px-6 py-2 rounded w-[75px] text-black dark:text-white opacity-60"
                                        @else
                                            class=" bg-gray-600 dark:bg-orange-500 hover:bg-gray-800 dark:hover:bg-orange-700 px-6 py-2 rounded w-[75px] text-white"
                                        @endif>
                                        <img src="{{ asset('img/produk/keranjang.png') }}" alt="keranjang">
                                    </button>
                                </div>
                            </div>
                            @else
                            <div>
                                <h3 class="text-2xl ml-4 font-extrabold">Jumlah</h3>
                                <div class="flex items-center ml-4 gap-3">
                                
                                    <input type="number" name="qty" value="1" min="1" class=" w-20 text-black rounded" max="{{ $produk->stok }}" @if($isStokHabis) disabled @endif>
                                    <button type="submit" 
                                        @if($isStokHabis) 
                                            disabled 
                                            class=" bg-gray-400 cursor-not-allowed px-6 py-2 rounded w-[75px] text-black dark:text-white opacity-60"
                                        @else
                                            class=" bg-gray-600 dark:bg-orange-500 hover:bg-gray-800 dark:hover:bg-orange-700 px-6 py-2 rounded w-[75px] text-white"
                                        @endif>
                                        <img src="{{ asset('img/produk/keranjang.png') }}" alt="keranjang">
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </form>    

            {{-- tampilan riwayat pembelian user untuk produk ini --}}
            @if(isset($userTransaksi) && $userTransaksi->isNotEmpty())
                <div class="mt-8">
                    <h3 class="text-xl font-bold text-black dark:text-white mb-2">Riwayat pembelian Anda untuk produk ini</h3>
                    <table class="w-full text-black dark:text-gray-300 text-sm table-auto border-collapse">
                        <thead>
                            <tr class="bg-[#FFF6ED] dark:bg-gray-700">
                                <th class="px-2 py-1 text-left">Tanggal</th>
                                <th class="px-2 py-1 text-left">Jumlah</th>
                                <th class="px-2 py-1 text-left">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userTransaksi as $detail)
                                <tr class="border-b border-[#E5E7EB] dark:border-gray-600">
                                    <td class="px-2 py-1">{{ $detail->transaksi->tanggal->format('d/m/Y') }}</td>
                                    <td class="px-2 py-1">{{ $detail->jumlah }}</td>
                                    <td class="px-2 py-1">Rp {{ number_format($detail->jumlah * $detail->harga,0,',','.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
    </div>
    

    {{-- ================= REKOMENDASI PRODUK ================= --}}
    <section class="mx-10 mt-16">
        <div class="border-t-2 border-[#E5E7EB] dark:border-orange-500 pt-6">
            <h3 class="text-2xl font-bold text-black dark:text-[#E67E22] mb-6 border-b-2 border-[#2C3E50] dark:border-[#E67E22] inline-block pb-1">
                Rekomendasi Produk
            </h3>

            @if($guest)
                <p class="text-gray-500 dark:text-gray-400 mt-2">
                    Silahkan daftar dan berbelanja dahulu untuk mendapatkan rekomendasi produk.
                </p>
            @elseif($isAdmin)
                <p class="text-gray-500 dark:text-gray-400 mt-2">
                    Admin tidak melihat rekomendasi produk.
                </p>
            @elseif($rekomendasi->isEmpty())
                <p class="text-gray-500 dark:text-gray-400 mt-2">
                    Silahkan berbelanja dahulu untuk mendapatkan rekomendasi produk.
                </p>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 mt-4">
                    @foreach($rekomendasi as $item)
                        <a href="{{ route('produk.show', $item->id) }}"
                           class="bg-[#FFF6ED] dark:bg-[#1A1A1A] p-4 rounded-xl hover:scale-105 transition-transform duration-200 shadow-md hover:shadow-lg h-full flex flex-col">
                            <img src="{{ asset('img/produk/'.$item->gambarproduk) }}"
                                 class="h-40 w-full object-cover rounded-lg mb-3"
                                 alt="{{ $item->nama }}">
                            <h4 class="font-bold text-black dark:text-white truncate">{{ $item->nama }}</h4>
                            <p class="text-black dark:text-[#E67E22] font-semibold mt-auto pt-2">
                                Rp {{ number_format($item->harga,0,',','.') }}
                            </p>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ================= PERHITUNGAN SIMILARITY ================= --}}
    @if(isset($similarityDebug) && !empty($similarityDebug))
        <section class="mx-10 mt-12 mb-10">
            <div class="border-t-2 border-[#E5E7EB] dark:border-orange-500 pt-6">
                <h3 class="text-2xl font-bold text-black dark:text-[#E67E22] mb-6 border-b-2 border-[#2C3E50] dark:border-[#E67E22] inline-block pb-1">
                    Similarity
                </h3>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">
                    {{-- Vektor Pembelian User --}}
                    <div class="bg-[#FFF6ED] dark:bg-[#1E1E1E] rounded-xl shadow-md p-6 h-full">
                        <h4 class="text-lg font-semibold text-black dark:text-white mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 bg-[#E67E22] rounded-full inline-block"></span>
                            Vektor Pembelian Anda (per Kategori)
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto text-sm border-collapse">
                                <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700 text-black dark:text-gray-200">
                                        <th class="px-4 py-2 text-left rounded-tl-lg">Kategori</th>
                                        <th class="px-4 py-2 text-left">Jumlah</th>
                                        <th class="px-4 py-2 text-left rounded-tr-lg">Produk yang Dibeli</th>
                                    </tr>
                                </thead>
                                <tbody class="text-black dark:text-gray-300">
                                    @foreach($similarityDebug['target'] as $cid => $qty)
                                        <tr class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-4 py-3 font-medium">{{ $similarityDebug['categoryNames'][$cid] ?? $cid }}</td>
                                            <td class="px-4 py-3">
                                                <span class="bg-[#E67E22]/20 text-[#E67E22] dark:text-orange-400 px-2 py-0.5 rounded font-semibold">
                                                    {{ $qty }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if(isset($similarityDebug['produkPerKategori'][$cid]))
                                                    <span class="text-xs text-gray-600 dark:text-gray-400">
                                                        {{ implode(', ', array_slice($similarityDebug['produkPerKategori'][$cid], 0, 5)) }}
                                                        @if(count($similarityDebug['produkPerKategori'][$cid]) > 5)
                                                            <span class="text-gray-400">...</span>
                                                        @endif
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Similarity Pengguna Lain --}}
                    <div class="bg-[#FFF6ED] dark:bg-[#1E1E1E] rounded-xl shadow-md p-6 h-full">
                        <h4 class="text-lg font-semibold text-black dark:text-white mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 bg-[#E67E22] rounded-full inline-block"></span>
                            Similarity dengan Pengguna Lain
                        </h4>
                        @php
                            $othersCollection = collect($similarityDebug['others'] ?? []);
                            $similarityAtas = $othersCollection->filter(fn ($d) => ($d['sim'] ?? 0) >= 0.5);
                            $similarityBawah = $othersCollection->filter(fn ($d) => ($d['sim'] ?? 0) < 0.5);
                        @endphp

                        <div class="space-y-6">
                            <div>
                                <h5 class="text-sm font-semibold text-[#E67E22] dark:text-orange-400 mb-2">Nilai Similarity >= 0.5</h5>
                                <div class="overflow-x-auto">
                                    <table class="w-full table-auto text-sm border-collapse">
                                        <thead>
                                            <tr class="bg-gray-200 dark:bg-gray-700 text-black dark:text-gray-200">
                                                <th class="px-4 py-2 text-left rounded-tl-lg">Pengguna</th>
                                                <th class="px-4 py-2 text-left">Nilai Similarity</th>
                                                <th class="px-4 py-2 text-left rounded-tr-lg">Vektor Pembelian (per Kategori)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-black dark:text-gray-300">
                                            @forelse($similarityAtas as $uid => $d)
                                                <tr class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                                                    <td class="px-4 py-3 align-top font-medium">{{ $similarityDebug['userNames'][$uid] ?? $uid }}</td>
                                                    <td class="px-4 py-3 align-top">
                                                        <span class="bg-[#E67E22]/20 text-[#E67E22] dark:text-orange-400 px-3 py-1 rounded-full font-bold text-base">
                                                            {{ number_format($d['sim'], 4) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="space-y-2">
                                                            @foreach($d['vector'] as $cid => $qty)
                                                                <div class="flex flex-col">
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="font-semibold text-black dark:text-white">{{ $similarityDebug['categoryNames'][$cid] ?? $cid }}</span>
                                                                        <span class="text-xs bg-gray-200 dark:bg-gray-600 px-2 py-0.5 rounded">{{ $qty }}</span>
                                                                    </div>
                                                                    @if(isset($similarityDebug['otherUsersProduk'][$uid][$cid]))
                                                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-4">
                                                                            {{ implode(', ', array_slice($similarityDebug['otherUsersProduk'][$uid][$cid], 0, 3)) }}{{ count($similarityDebug['otherUsersProduk'][$uid][$cid]) > 3 ? '...' : '' }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">Tidak ada pengguna dengan similarity >= 0.5.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div>
                                <h5 class="text-sm font-semibold text-[#E67E22] dark:text-orange-400 mb-2">Nilai Similarity &lt; 0.5</h5>
                                <div class="overflow-x-auto">
                                    <table class="w-full table-auto text-sm border-collapse">
                                        <thead>
                                            <tr class="bg-gray-200 dark:bg-gray-700 text-black dark:text-gray-200">
                                                <th class="px-4 py-2 text-left rounded-tl-lg">Pengguna</th>
                                                <th class="px-4 py-2 text-left">Nilai Similarity</th>
                                                <th class="px-4 py-2 text-left rounded-tr-lg">Vektor Pembelian (per Kategori)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-black dark:text-gray-300">
                                            @forelse($similarityBawah as $uid => $d)
                                                <tr class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                                                    <td class="px-4 py-3 align-top font-medium">{{ $similarityDebug['userNames'][$uid] ?? $uid }}</td>
                                                    <td class="px-4 py-3 align-top">
                                                        <span class="bg-[#E67E22]/20 text-[#E67E22] dark:text-orange-400 px-3 py-1 rounded-full font-bold text-base">
                                                            {{ number_format($d['sim'], 4) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="space-y-2">
                                                            @foreach($d['vector'] as $cid => $qty)
                                                                <div class="flex flex-col">
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="font-semibold text-black dark:text-white">{{ $similarityDebug['categoryNames'][$cid] ?? $cid }}</span>
                                                                        <span class="text-xs bg-gray-200 dark:bg-gray-600 px-2 py-0.5 rounded">{{ $qty }}</span>
                                                                    </div>
                                                                    @if(isset($similarityDebug['otherUsersProduk'][$uid][$cid]))
                                                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-4">
                                                                            {{ implode(', ', array_slice($similarityDebug['otherUsersProduk'][$uid][$cid], 0, 3)) }}{{ count($similarityDebug['otherUsersProduk'][$uid][$cid]) > 3 ? '...' : '' }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">Tidak ada pengguna dengan similarity &lt; 0.5.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <script>
        const slider = document.getElementById('slider');
        const dots = document.querySelectorAll('.dot');
        let currentIndex = 0;
        const totalSlides = dots.length;
      
        function updateSlider() {
          // Geser slider berdasarkan index
          slider.style.transform = `translateX(-${currentIndex * 100}%)`;
          
          // Update warna indikator
          dots.forEach((dot, index) => {
            dot.classList.toggle('bg-white', index === currentIndex);
            dot.classList.toggle('bg-white/50', index !== currentIndex);
          });
        }
      
        function nextSlide() {
          currentIndex = (currentIndex + 1) % totalSlides;
          updateSlider();
        }
      
        // Jalankan animasi otomatis setiap 3 detik
        setInterval(nextSlide, 3000);
      
        // Inisialisasi tampilan pertama
        updateSlider();

        const qtyInput = document.getElementById('qty_input');
        const sizeRadios = document.querySelectorAll('.ukuran-radio');

        if (qtyInput && sizeRadios.length) {
            const updateQtyLimitBySize = () => {
                const selected = document.querySelector('.ukuran-radio:checked');
                const stock = selected ? parseInt(selected.dataset.stock || '0', 10) : 1;
                qtyInput.max = stock > 0 ? stock : 1;

                if (parseInt(qtyInput.value || '1', 10) > qtyInput.max) {
                    qtyInput.value = qtyInput.max;
                }
            };

            sizeRadios.forEach((radio) => {
                radio.addEventListener('change', updateQtyLimitBySize);
            });

            updateQtyLimitBySize();
        }
    </script>
</x-app-layout>