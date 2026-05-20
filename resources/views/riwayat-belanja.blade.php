<x-app-layout>
    <div class="mb-5 mt-5 flex justify-center">
        <h3 class="text-4xl font-extrabold text-black dark:text-[#E67E22]">Riwayat Belanja</h3>
    </div>

    @if($transaksis->isEmpty())
        <div class="flex justify-center mt-10">
            <div class="text-center">
                <p class="text-xl text-gray-500 mb-4">Belum ada riwayat belanja</p>
                <a href="{{ route('produk') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22] text-white rounded-lg dark:hover:bg-orange-600">
                    Mulai Belanja
                </a>
            </div>
        </div>
    @else
        <div class="flex justify-center mb-8">
            <div class="w-full max-w-4xl px-4">
                <div class="space-y-4">
                    @foreach($transaksis as $transaksi)
                        <div class="bg-gray-300 dark:bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-sm text-gray-500">
                                        Tanggal: <span class="font-semibold text-gray-700">{{ $transaksi->tanggal->format('d/m/Y ') }}</span>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Metode: <span class="font-semibold text-gray-700">{{ $transaksi->metode->nama ?? '-' }}</span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-black dark:text-[#E67E22]">
                                        Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                                    </p>
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold 
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

                            <div class="border-t pt-3 mb-3">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Item yang dibeli:</p>
                                <div class="space-y-2">
                                    @foreach($transaksi->detail as $detail)
                                        <div class="flex justify-between text-sm text-gray-600">
                                            <span>{{ $detail->produk->nama ?? 'Produk Dihapus' }} ({{ $detail->jumlah }} x)</span>
                                            <span>Rp {{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex justify-end gap-2">
                                <a href="{{ route('riwayat-belanja.detail', $transaksi->id) }}" 
                                   class="px-4 py-2 bg-gray-600 hover:bg-gray-800 dark:bg-[#E67E22] text-white rounded-lg dark:hover:bg-orange-600 transition text-sm font-semibold">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    {{ $transaksis->links() }}
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
