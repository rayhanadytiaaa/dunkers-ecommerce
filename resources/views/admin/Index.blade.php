<x-app-layout>
    <div class="p-6">
        <h1 class="text-4xl font-bold mb-5 text-black dark:text-orange-500">Dashboard Admin</h1>
        
        <div class="grid grid-cols-3 gap-4">
        <div class="bg-gray-600 dark:bg-[#E67E22] text-white p-4 rounded-xl flex items-center justify-between">
            <div>
                Total Produk: {{ $totalProduk }}
            </div>
            <button class=" px-3 py-2 ml-5 bg-gray-800 hover:bg-gray-900 dark:bg-orange-800 dark:hover:bg-orange-700 rounded-xl">
                <a href="{{ route('admin.produk.index') }}">Kelola Produk</a>
            </button>
        </div>

        <div class="bg-gray-600 dark:bg-[#E67E22] text-white p-4 rounded-xl flex items-center justify-between">
            <div>
                Total Transaksi: {{ $totalTransaksi }}
            </div>
            <button class=" px-3 py-2 ml-5 bg-gray-800 hover:bg-gray-900 dark:bg-orange-800 dark:hover:bg-orange-700 rounded-xl">
                <a href="{{ route('admin.riwayat') }}">Riwayat Transaksi</a>
            </button>
        </div>

        <div class="bg-gray-600 dark:bg-[#E67E22] text-white p-4 rounded-xl flex items-center justify-between">
            <div>
                Total Pengguna: {{ $totalUser }}
            </div>
            <button class=" px-3 py-2 ml-5 bg-gray-800 hover:bg-gray-900 dark:bg-orange-800 dark:hover:bg-orange-700 rounded-xl">
                <a href="{{ route('admin.users') }}">Kelola Pengguna</a>
            </button>
        </div>
        </div>
    </div>
    
    <br><br><br><br><br><br><br><br><br><br><br><br>
</x-app-layout>