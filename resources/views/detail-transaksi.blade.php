<x-app-layout>
    <div class="mb-5 mt-5 flex justify-center">
        <h3 class="text-4xl font-extrabold text-black dark:text-[#E67E22]">Detail Transaksi</h3>
    </div>

    <div class="flex justify-center mb-8">
        <div class="w-full max-w-2xl px-4">
            <!-- Info Transaksi -->
            <div class="bg-gray-300 dark:bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Informasi Transaksi</h2>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">ID Transaksi</p>
                        <p class="text-lg font-semibold text-gray-800">#{{ $transaksi->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $transaksi->tanggal->format('d/m/Y ') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Metode Pembayaran</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $transaksi->metode->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="inline-block px-3 py-1 rounded-full font-semibold 
                            @if($transaksi->status == 'paid')
                                bg-green-100 text-green-800
                            @elseif($transaksi->status == 'proses')
                                bg-blue-100 text-blue-800
                            @elseif($transaksi->status == 'pending')
                                bg-yellow-100 text-yellow-800
                            @else
                                bg-red-100 text-red-800
                            @endif
                        ">
                            {{ ucfirst($transaksi->status) }}
                        </span>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500">Pembeli</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $transaksi->user->name ?? 'Akun telah dihapus' }}</p>
                </div>
            </div>

            <!-- Detail Item -->
            <div class="bg-gray-300 dark:bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Item yang Dibeli</h2>
                
                <div class="space-y-4">
                    @forelse($transaksi->detail as $detail)
                        <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                            <div class="flex gap-4">
                                @if($detail->produk)
                                    <div class="w-24 h-24 flex-shrink-0">
                                        <img src="{{ asset('img/produk/' . $detail->produk->gambarproduk) }}" 
                                             alt="{{ $detail->produk->nama }}" 
                                             class="w-full h-full object-cover rounded-lg">
                                    </div>
                                @endif
                                
                                <div class="flex-grow">
                                    <h3 class="font-semibold text-gray-800">
                                        {{ $detail->produk->nama ?? 'Produk Dihapus' }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Kuantitas: {{ $detail->jumlah }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Harga Satuan</p>
                                    <p class="font-semibold text-gray-800">Rp {{ number_format($detail->harga, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-500 mt-2">Subtotal</p>
                                    <p class="text-lg font-bold text-black dark:text-[#E67E22]">
                                        Rp {{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">Tidak ada item dalam transaksi ini</p>
                    @endforelse
                </div>
            </div>

            <!-- Ringkasan Total -->
            <div class="bg-gray-300 dark:bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-gray-600">Subtotal</p>
                    <p class="font-semibold text-gray-800">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</p>
                </div>
                <div class="border-t pt-3">
                    <div class="flex justify-between items-center">
                        <p class="text-xl font-bold text-gray-800">Total</p>
                        <p class="text-2xl font-bold text-black dark:text-[#E67E22]">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-3 justify-end">
                <a href="{{ route('riwayat-belanja') }}" 
                   class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-800 transition font-semibold">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
