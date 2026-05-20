<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-10 bg-white dark:bg-[#1F1F1F] rounded-3xl py-8 px-4 shadow-md ring-1 ring-slate-200 dark:ring-0">
                <p class="inline-flex items-center rounded-full px-4 py-1 text-xs sm:text-sm font-semibold tracking-wide bg-blue-600 text-white dark:bg-[#E67E22] dark:text-black mb-4">
                    Selamat Datang
                </p>
                <h1 class="text-slate-800 dark:text-[#E67E22] text-3xl sm:text-4xl font-extrabold mb-2">
                    Temukan Produk Favoritmu di Dunkers
                </h1>
            </div>

            <div class="mb-8 flex justify-center">
                <h3 class="text-3xl sm:text-4xl font-bold text-slate-800 dark:text-[#E67E22] border-b-2 border-slate-300 dark:border-[#E67E22] pb-2">
                    Produk Terlaris
                </h3>
            </div>

            @if ($produkTerlaris->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-8">
                    @foreach ($produkTerlaris as $item)
                        @if($item->produk)
                            <div class="bg-white dark:bg-[#1A1A1A] rounded-3xl p-3 shadow-lg shadow-slate-200/80 dark:shadow-slate-800/60 ring-1 ring-slate-200 dark:ring-0">
                                <div class="h-full rounded-2xl bg-slate-50 dark:bg-[#E67E22] p-4 flex flex-col text-center border border-slate-200 dark:border-transparent">
                                    <div class="w-full aspect-square rounded-2xl overflow-hidden bg-white mb-4 flex items-center justify-center p-3 border border-slate-200 dark:bg-white/20 dark:border-transparent">
                                        <img
                                            src="{{ asset('storage/img/produk/' . $item->produk->gambarproduk) }}"
                                            alt="{{ $item->produk->nama }}"
                                            class="max-w-full max-h-full object-contain object-center hover:scale-105 transition-transform duration-300"
                                        >
                                    </div>

                                    <h4 class="text-slate-800 dark:text-white text-lg font-bold mb-2 line-clamp-2 min-h-[56px]">
                                        {{ $item->produk->nama }}
                                    </h4>

                                    <p class="text-slate-600 dark:text-gray-100 text-sm mb-3 line-clamp-2 min-h-[40px] px-1">
                                        {{ $item->produk->deskripsi }}
                                    </p>

                                    <div class="text-blue-700 dark:text-white text-xl font-bold mb-2">
                                        Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                                    </div>

                                    <p class="text-xs font-semibold text-slate-500 dark:text-white/90 mb-4">
                                        Terjual: {{ $item->total_terjual }}
                                    </p>

                                    <a href="{{ route('produk.show', $item->produk->id) }}"
                                       class="mt-auto bg-blue-600 text-white dark:bg-white dark:text-orange-500 px-4 py-2 rounded-full font-bold text-sm shadow-md hover:shadow-lg hover:bg-blue-700 dark:hover:bg-gray-200 transition-all active:scale-95">
                                        Lihat Produk
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <p class="text-gray-500 dark:text-gray-300 text-lg font-semibold">Belum ada data penjualan produk.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
