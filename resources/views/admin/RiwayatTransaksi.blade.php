<x-app-layout>
    <div class="p-6">
        <h1 class="text-4xl font-bold mb-4 text-black dark:text-orange-500 text-center">Riwayat Transaksi</h1>
        
        
        <div class=" flex gap-4 mb-4 mt-10">
            <form method="GET" class="flex gap-4 mb-3">
                <input type="date" name="from" value="{{ request('from') }}" class=" bg-white dark:bg-[#1A1A1A] text-black dark:text-white dark:border-white border-black hover:border-gray-500 p-2 rounded-lg">
                <input type="date" name="to" value="{{ request('to') }}" class=" bg-white dark:bg-[#1A1A1A] text-black dark:text-white dark:border-white border-black hover:border-gray-500 p-2 rounded-lg">
                <button class="bg-gray-600 hover:bg-gray-800 dark:bg-orange-600 dark:hover:bg-orange-500 text-white px-4 py-2 rounded-lg">Filter</button>
            </form>
            <button class=" mb-3">
                <a href="{{ route('admin.riwayat.pdf', request()->query()) }}"
                    class="bg-green-600 hover:bg-green-800 text-white rounded-lg py-3 px-3">
                     Download PDF
                 </a>
            </button>
        </div>
        
            <table class="w-full">
                <thead class="text-white bg-gray-600 dark:bg-orange-500">
                <tr>
                <th class="p-2">No</th>
                <th class="p-2">User</th>
                <th class="p-2">Tanggal</th>
                <th class="p-2">Produk</th>
                <th class="p-2">Jumlah</th>
                <th class="p-2">Total harga</th>
                </tr>
                </thead>
                <tbody>
                @forelse($transaksi as $item)
                    <tr class=" text-black dark:text-white text-center border-b border-gray-400">
                        <td class="p-2 text-center">
                            {{ ($transaksi->currentPage() - 1) * $transaksi->perPage() + $loop->iteration }}
                        </td>
                    
                        <td class="p-2">
                            {{ $item->user->name ?? '-' }}
                        </td>
                    
                        <td class="p-2">
                            {{ $item->tanggal->format('d/m/Y') }}
                        </td>
                    
                        <td class="p-2">
                            @foreach($item->detail as $d)
                                <div>{{ $d->produk->nama ?? '-' }}</div>
                            @endforeach
                        </td>
                    
                        <td class="p-2 text-center">
                            @foreach($item->detail as $d)
                                <div>{{ $d->jumlah }}</div>
                            @endforeach
                        </td>
                    
                        <td class="p-2">
                            Rp {{ number_format($item->total,0,',','.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center p-4">Tidak ada transaksi</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $transaksi->links() }}
            </div>            
        </div>

        <br><br>
</x-app-layout>