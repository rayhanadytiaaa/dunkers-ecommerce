<x-app-layout>
    <div class="mb-5 mt-5 flex justify-center">
        <h3 class="text-4xl font-extrabold text-black dark:text-[#E67E22]">Checkout</h3>
    </div>

    @if($carts->isEmpty())
        <div class="flex justify-center mt-10">
            <div class="text-center">
                <p class="text-xl text-gray-500 mb-4">Keranjang Anda kosong</p>
                <a href="{{ route('produk') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22] text-white rounded-lg dark:hover:bg-orange-600 transition">
                    Mulai Belanja
                </a>
            </div>
        </div>
    @else
        <div class="flex justify-center mb-8">
            <div class="w-full max-w-4xl px-4">
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf

                    <!-- Ringkasan Pesanan -->
                    <div class="bg-gray-300 dark:bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Ringkasan Pesanan</h2>

                        <div class="space-y-4">
                            @php $total = 0; @endphp
                            @foreach($carts as $item)
                                @php
                                    $subtotal = $item->produk->harga * $item->qty;
                                    $total += $subtotal;
                                @endphp

                                <div class="flex items-center gap-4 p-4 border rounded-lg hover:bg-gray-50 transition">
                                    <div class="w-16 h-16 flex-shrink-0">
                                        <img src="{{ asset('storage/img/produk/' . $item->produk->gambarproduk) }}"
                                             alt="{{ $item->produk->nama }}"
                                             class="w-full h-full object-cover rounded-lg">
                                    </div>

                                    <div class="flex-grow">
                                        <h3 class="font-semibold text-gray-800">{{ $item->produk->nama }}</h3>
                                        <p class="text-sm text-gray-500">
                                            Ukuran: {{ $item->ukuran->nama ?? 'Tidak ada' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Kuantitas: {{ $item->qty }}
                                        </p>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Harga Satuan</p>
                                        <p class="font-semibold text-gray-800">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-500 mt-1">Subtotal</p>
                                        <p class="font-bold text-black dark:text-[#E67E22]">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total -->
                        <div class="border-t mt-6 pt-4">
                            <div class="flex justify-between items-center">
                                <p class="text-xl font-bold text-gray-800">Total Pembayaran</p>
                                <p class="text-2xl font-bold text-black dark:text-[#E67E22]">Rp {{ number_format($total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="bg-gray-300 dark:bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Metode Pembayaran</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($metodes as $m)
                                <label class="relative">
                                    <input type="radio"
                                           name="metode_pembayaran_id"
                                           value="{{ $m->id }}"
                                           class="peer sr-only"
                                           required>

                                    <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer
                                                dark:peer-checked:border-[#E67E22] dark:peer-checked:bg-orange-500
                                                dark:hover:border-[#E67E22] 
                                                peer-checked:border-gray-600 peer-checked:bg-gray-500
                                                hover:border-gray-800
                                                transition">
                                        <div class="flex items-center gap-3">
                                            <div class="w-4 h-4 border-2 border-gray-300 rounded-full
                                                        dark:peer-checked:border-[#E67E22] dark:peer-checked:bg-[#E67E22]
                                                        peer-checked:border-gray-600 peer-checked:bg-gray-800
                                                        flex items-center justify-center">
                                                <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                            </div>
                                            <span class="font-semibold text-gray-800">{{ $m->nama }}</span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tombol Checkout -->
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('cart.index') }}"
                           class="px-8 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-800 transition font-semibold">
                            Kembali ke Keranjang
                        </a>

                        <button type="submit"
                                class="px-8 py-3 bg-green-600 hover:bg-green-800 dark:bg-[#E67E22] text-white rounded-lg dark:hover:bg-orange-600 transition font-semibold">
                            Bayar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
    