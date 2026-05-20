<x-app-layout>
    <div class="mb-5 mt-5 flex justify-center">
        <h3 class="text-4xl font-extrabold text-black dark:text-[#E67E22]">Keranjang Belanja</h3>
    </div>

    @if($carts->isEmpty())
        <div class="flex justify-center mt-10 mb-10">
            <div class="text-center">
                <p class="text-xl text-black dark:text-white mb-4">Keranjang Anda kosong</p>
                <a href="{{ route('produk') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22] text-white rounded-lg dark:hover:bg-orange-600 transition">
                    Mulai Belanja
                </a>
            </div>
        </div>
    @else
        <div class="flex justify-center mb-8">
            <div class="w-full max-w-4xl px-4">
                <!-- Daftar Item Keranjang -->
                <div class="space-y-4 mb-6">
                    @php $grandTotal = 0; @endphp

                    @foreach($carts as $item)
                        @php
                            $subtotal = $item->produk->harga * $item->qty;
                            $grandTotal += $subtotal;
                        @endphp

                        <div class="dark:bg-white bg-gray-300 rounded-lg shadow-md p-6 hover:shadow-lg transition">
                            <div class="flex items-center gap-4">
                                <!-- Gambar Produk -->
                                <div class="w-20 h-20 flex-shrink-0">
                                    <img src="{{ asset('storage/img/produk/' . $item->produk->gambarproduk) }}"
                                         alt="{{ $item->produk->nama }}"
                                         class="w-full h-full object-cover rounded-lg">
                                </div>

                                <!-- Detail Produk -->
                                <div class="flex-grow">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $item->produk->nama }}</h3>
                                    <p class="text-sm text-gray-500 mb-1">
                                        Ukuran: {{ $item->ukuran->nama ?? 'Tidak ada' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Stok tersedia: {{ $item->produk->stok }}
                                    </p>

                                    @if($item->produk->stok <= 0)
                                        <p class="text-sm text-red-600 font-semibold mt-1">
                                            ⚠️ Stok habis
                                        </p>
                                    @endif
                                </div>

                                <!-- Kontrol Kuantitas -->
                                <div class="flex items-center gap-3">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')

                                        <button type="button"
                                                onclick="updateQty(this, -1)"
                                                class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold"
                                                @if($item->qty <= 1) disabled @endif>
                                            -
                                        </button>

                                        <input type="number"
                                               name="qty"
                                               value="{{ $item->qty }}"
                                               min="1"
                                               max="{{ $item->produk->stok }}"
                                               class="w-16 text-center border rounded px-2 py-1"
                                               onchange="this.form.submit()">

                                        <button type="button"
                                                onclick="updateQty(this, 1)"
                                                class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold"
                                                @if($item->qty >= $item->produk->stok) disabled @endif>
                                            +
                                        </button>
                                    </form>
                                </div>

                                <!-- Harga dan Aksi -->
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Harga Satuan</p>
                                    <p class="font-semibold text-gray-800">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-500 mt-1">Subtotal</p>
                                    <p class="text-lg font-bold text-black dark:text-[#E67E22]">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>

                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-white bg-red-800 hover:bg-red-900 hover:text-white py-2 px-3 text-sm font-semibold hover:underline rounded-xl">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Ringkasan dan Checkout -->
                <div class="bg-gray-300 dark:bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Ringkasan Belanja</h3>
                        <p class="text-2xl font-bold text-black dark:text-[#E67E22]">Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                    </div>

                    @php
                        $hasOutOfStockItems = false;
                        foreach($carts as $item) {
                            if($item->produk->stok <= 0) {
                                $hasOutOfStockItems = true;
                                break;
                            }
                        }
                    @endphp

                    @if($hasOutOfStockItems)
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                            <div class="flex items-center">
                                <span class="text-red-500 text-xl mr-2">⚠️</span>
                                <p class="font-semibold">Beberapa produk stoknya telah habis</p>
                            </div>
                            <p class="text-sm mt-1">Silakan hapus produk tersebut atau kurangi jumlahnya untuk melanjutkan checkout.</p>
                        </div>
                    @endif

                    <div class="flex justify-between gap-4 ">
                        <a href="{{ route('produk') }}"
                           class="flex-1 px-6 py-3 bg-gray-400 text-gray-800 rounded-lg hover:bg-gray-400 transition font-semibold text-center">
                            Lanjut Belanja
                        </a>

                        @if($hasOutOfStockItems)
                            <button disabled
                                    class="flex-1 px-6 py-3 bg-gray-400 cursor-not-allowed text-white rounded-lg font-semibold opacity-60">
                                Checkout Tidak Tersedia
                            </button>
                        @else
                            <a href="{{ route('checkout.page') }}"
                               class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-800 dark:bg-[#E67E22] text-white rounded-lg dark:hover:bg-orange-600 transition font-semibold text-center">
                                Checkout
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function updateQty(button, change) {
            const form = button.closest('form');
            const input = form.querySelector('input[name="qty"]');
            const currentValue = parseInt(input.value);
            const newValue = currentValue + change;

            // Validasi batas minimum dan maksimum
            const min = parseInt(input.min) || 1;
            const max = parseInt(input.max) || 999;

            if (newValue >= min && newValue <= max) {
                input.value = newValue;
                form.submit();
            }
        }
    </script>
</x-app-layout>
    