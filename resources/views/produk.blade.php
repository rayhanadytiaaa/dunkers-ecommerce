<x-app-layout>
    
    <div class=" ml-16 mb-5 mt-10 flex justify-center">
        <h3 class=" text-5xl font-extrabold text-black dark:text-[#E67E22] border-[#2C3E50] dark:border-[#E67E22]">Semua Produk!</h3>
    </div>
    
    <div class="w-full max-w-2xl mx-auto p-4 mb-10 ">
        <form id="produk-filter-form" action="{{ route('produk') }}" method="GET" class="space-y-4">
            <div class="relative flex items-center group">
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari Baju, Sepatu atau Aksesoris..." 
                    class="block w-full h-16 pl-6 pr-32 bg-white dark:bg-[#E67E22] border border-slate-200 rounded-full shadow-md text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300 outline-none"
                >

                <button 
                    type="submit" 
                    class="absolute right-2 top-2 bottom-2 px-6 bg-emerald-600 hover:bg-emerald-700 dark:bg-orange-900 dark:hover:bg-orange-700 text-white font-medium rounded-full shadow-md hover:shadow-lg transform active:scale-95 transition-all duration-200"
                >
                    Cari Sekarang
                </button>
            </div>

        </form>
    </div>

    {{-- SEMUA PRODUK --}}
    <div class="flex justify-center">
        <div id="produk-wrapper" class="py-6 w-full">
            @if($produkPage->isNotEmpty())
                <div id="produk-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-20 px-10">
                    @foreach($produkPage as $item)
                        <div class="produk-item h-[400px] w-[200px] bg-[#FFF6ED] dark:bg-[#1A1A1A] flex items-center justify-center justify-self-center">
                            <div class="relative mt-5 w-[200px] bg-gray-600 dark:bg-[#E67E22] rounded-[30px] shadow-xl shadow-slate-600 text-center hover:bg-gray-800 dark:hover:bg-orange-600">
                                {{-- GAMBAR --}}
                                <img
                                    src="{{ asset('img/produk/' . $item->gambarproduk) }}"
                                    alt="{{ $item->nama }}"
                                    class="absolute -top-36 left-1/2 -translate-x-1/2 w-80 drop-shadow-2xl hover:animate-pulse"
                                >

                                <div class="pt-12 pb-8 text-white">
                                    {{-- NAMA --}}
                                    <h3 class="text-xl font-bold mb-2">
                                        {{ $item->nama }}
                                    </h3>

                                    <p class=" line-clamp-2 text-sm mb-2 opacity-90 px-2 text-gray-200 ">
                                        {{$item->deskripsi}}
                                    </p>

                                    {{-- HARGA --}}
                                    <div class="text-xl font-bold mb-4">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </div>
                                    <div class="flex items-center justify-center">
                                        <a href="{{ route('produk.show', $item->id) }}"
                                            class="bg-white dark:bg-white text-black dark:text-orange-500 px-4 py-1 rounded-full font-bold text-sm shadow-md hover:shadow-lg dark:hover:bg-[#D35400] hover:bg-gray-500 transition-all active:scale-95">
                                            Lihat Produk
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-400 py-10">
                    <p>Tidak ada produk yang ditemukan</p>
                </div>
            @endif
        </div>      
    </div>

    <div class="mt-8 mb-10 px-10 flex flex-col items-center justify-center gap-4">
        @if ($produkPage->hasMorePages())
            <button
                id="load-more-btn"
                type="button"
                data-next-url="{{ $produkPage->nextPageUrl() }}"
                class="px-6 py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 dark:bg-orange-900 dark:hover:bg-orange-700 text-white font-semibold transition-colors"
            >
                Selanjutnya
            </button>
        @endif

        <p id="end-caption" class="text-slate-500 dark:text-slate-300 text-sm {{ $produkPage->hasMorePages() ? 'hidden' : '' }}">
            Produknya sudah habis. Coba kata kunci lain.
        </p>
    </div>

    <script>
        const produkGrid = document.getElementById('produk-grid');
        const loadMoreBtn = document.getElementById('load-more-btn');
        const endCaption = document.getElementById('end-caption');

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', async function () {
                const nextUrl = loadMoreBtn.dataset.nextUrl;
                if (!nextUrl) {
                    loadMoreBtn.remove();
                    endCaption.classList.remove('hidden');
                    return;
                }

                loadMoreBtn.disabled = true;
                loadMoreBtn.textContent = 'Memuat...';

                try {
                    const response = await fetch(nextUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Gagal memuat produk berikutnya');
                    }

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const nextItems = doc.querySelectorAll('#produk-grid .produk-item');

                    if (nextItems.length === 0) {
                        loadMoreBtn.remove();
                        endCaption.classList.remove('hidden');
                        return;
                    }

                    nextItems.forEach((item) => {
                        produkGrid.insertAdjacentHTML('beforeend', item.outerHTML);
                    });

                    const nextButtonOnFetchedPage = doc.getElementById('load-more-btn');
                    if (nextButtonOnFetchedPage && nextButtonOnFetchedPage.dataset.nextUrl) {
                        loadMoreBtn.dataset.nextUrl = nextButtonOnFetchedPage.dataset.nextUrl;
                        loadMoreBtn.disabled = false;
                        loadMoreBtn.textContent = 'Selanjutnya';
                    } else {
                        loadMoreBtn.remove();
                        endCaption.classList.remove('hidden');
                    }
                } catch (error) {
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.textContent = 'Coba Lagi';
                }
            });
        }
    </script>

</x-app-layout>