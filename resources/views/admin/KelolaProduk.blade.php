<x-app-layout>
    <div class="p-6">
        <h1 class=" text-4xl font-bold mb-4 text-black dark:text-orange-500 text-center">Kelola Produk</h1>
        
        
        <a href="{{ route('admin.produk.create') }}" 
        class="bg-gray-600 hover:bg-gray-800 dark:bg-orange-600 dark:hover:bg-orange-500 text-white px-4 py-2 rounded-lg text-lg">Tambah Produk
        </a>
        
        <table class="w-full mt-5">
            <thead>
            <tr class="bg-gray-600 text-white dark:bg-orange-500 ">
                <th class="p-2">No</th>
                <th class="p-2">Nama</th>
                <th class="p-2">Harga</th>
                <th class="p-2">Kategori</th>
                <th class="p-2">Merek</th>
                <th class="p-2">Stok</th>
                <th class="p-2">Aksi</th>
            </tr>
            </thead>
            <tbody class=" text-black dark:text-white text-center ">
            @foreach($produk as $item)
            <tr class="border-b border-gray-400">
                <td class="p-2">
                    {{ ($produk->currentPage() - 1) * $produk->perPage() + $loop->iteration }}
                </td>                
                <td class="p-2">{{ $item->nama }}</td>
                <td class="p-2">Rp {{ number_format($item->harga,0,',','.') }}</td>
                <td>{{ $item->kategori->nama }}</td>
                <td>{{ $item->merek->nama }}</td>
                <td class="p-2">{{ $item->stok }}</td>
                <td class="p-2 flex gap-2 justify-center">
                <a href="{{ route('admin.produk.edit',$item->id) }}" class="bg-gray-600 text-white hover:bg-gray-800 dark:bg-orange-600 dark:hover:bg-orange-500 px-2 py-1 rounded">Edit</a>
                <form action="{{ route('admin.produk.destroy',$item->id) }}" method="POST">
                @csrf @method('DELETE')
                <button class="bg-red-600 hover:bg-red-500 text-white px-2 py-1 rounded">Hapus</button>
                </form>
            </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-5 ">
            {{ $produk->links() }}
        </div>        
    </div>
</x-app-layout>