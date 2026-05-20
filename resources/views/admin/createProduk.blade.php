<x-app-layout>
    <div class="p-6 max-w-xl mx-auto text-white bg-gray-600 dark:bg-[#E67E22] m-5 rounded-xl">
        <h1 class="text-2xl font-bold mb-4">Tambah Produk</h1>
        @if ($errors->any())
            <div class="bg-red-100 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-red-600">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
    
            <div class="mb-3 ">
                <label>Nama Produk</label>
                <input type="text" name="nama" class="w-full border p-2 rounded-lg bg-gray-800 dark:bg-orange-900 text-white" required>
            </div>
    
            <div class="mb-3">
                <label class="text-white">Harga</label>
                <div class="flex items-center">
                    <span class="bg-gray-700 p-2 rounded-l-lg text-white border-y border-l">Rp</span>
                    <input type="text" 
                        id="inputHarga" 
                        name="harga" 
                        class="w-full border p-2 rounded-r-lg bg-gray-800 dark:bg-orange-900 text-white" 
                        placeholder="0"
                        required>
                </div>
            </div>

            <div class="mb-3">
                <label>Kategori</label>
                <select id="kategori_id" name="kategori_id" class="w-full border p-2 rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label>Merek</label>
                <select name="merek_id" class="w-full border p-2 rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
                    <option value="">-- Pilih Merek --</option>
                    @foreach($merek as $m)
                        <option value="{{ $m->id }}">{{ $m->nama }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3" id="stok-umum-wrapper">
                <label>Stok</label>
                <input type="number" id="stok_umum" name="stok" class="w-full border p-2 rounded-lg bg-gray-800 dark:bg-orange-900 text-white" required>
            </div>

            <div class="mb-3 hidden" id="stok-ukuran-wrapper">
                <label>Stok per Ukuran</label>
                <div id="stok-ukuran-list" class="grid grid-cols-2 gap-3 mt-2"></div>
                <p class="mt-3 font-semibold">Total Stok: <span id="stok-total-value">0</span></p>
            </div>
    
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="w-full border p-2 rounded-lg bg-gray-800 dark:bg-orange-900 text-white"></textarea>
            </div>
    
            <div class=" grid grid-cols-2 gap-4">
                <div class="mb-3">
                    <label>Gambar Produk (wajib png)</label>
                    <input type="file" accept="image/png" name="gambarproduk" class="w-full" >
                </div>
    
                <div class="mb-3">
                    <label>Gambar Produk 1</label>
                    <input type="file" accept="image/png,image/jpeg,image/jpg,image/avif" name="gambarproduk1" class="w-full" >
                </div>
    
                <div class="mb-3">
                    <label>Gambar Produk 2</label>
                    <input type="file" accept="image/png,image/jpeg,image/jpg,image/avif" name="gambarproduk2" class="w-full">
                </div>
    
                <div class="mb-3">
                    <label>Gambar Produk 3</label>
                    <input type="file" accept="image/png,image/jpeg,image/jpg,image/avif" name="gambarproduk3" class="w-full">
                </div>
            </div>
    
            <div class="flex justify-end">
                <button class="bg-green-600 hover:bg-green-800 text-white px-4 py-2 rounded-lg">Simpan</button>
                {{-- <button class=" ml-2 bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg"><a href="{{ route('admin.produk.index') }}">Kembali</a></button> --}}
            </div>
        </form>
    </div>

    @php
        $kategoriUkuranMap = $kategori->mapWithKeys(function ($k) {
            return [
                $k->id => $k->ukurans->map(function ($u) {
                    return ['id' => $u->id, 'nama' => $u->nama];
                })->values(),
            ];
        });
    @endphp

    <script id="kategori-ukuran-json" type="application/json">@json($kategoriUkuranMap)</script>

    <script>
        const inputHarga = document.getElementById('inputHarga');
        const kategoriSelect = document.getElementById('kategori_id');
        const stokUmumWrapper = document.getElementById('stok-umum-wrapper');
        const stokUmumInput = document.getElementById('stok_umum');
        const stokUkuranWrapper = document.getElementById('stok-ukuran-wrapper');
        const stokUkuranList = document.getElementById('stok-ukuran-list');

        const kategoriUkurans = JSON.parse(document.getElementById('kategori-ukuran-json').textContent || '{}');

        inputHarga.addEventListener('input', function(e) {
            // 1. Ambil value, hapus semua karakter kecuali angka
            let value = this.value.replace(/[^0-9]/g, '');
            
            // 2. Format menjadi ribuan dengan titik
            if (value) {
                this.value = formatRupiah(value);
            } else {
                this.value = '';
            }
        });

        function formatRupiah(angka) {
            let numberString = angka.toString();
            let sisa = numberString.length % 3;
            let rupiah = numberString.substr(0, sisa);
            let ribuan = numberString.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return rupiah;
        }

        function renderStokByKategori() {
            const ukuranList = kategoriUkurans[kategoriSelect.value] || [];
            const hasUkuran = ukuranList.length > 0;

            stokUmumWrapper.classList.toggle('hidden', hasUkuran);
            stokUkuranWrapper.classList.toggle('hidden', !hasUkuran);
            stokUmumInput.required = !hasUkuran;

            stokUkuranList.innerHTML = '';

            if (!hasUkuran) {
                document.getElementById('stok-total-value').textContent = '0';
                return;
            }

            ukuranList.forEach((ukuran) => {
                const row = document.createElement('div');
                row.innerHTML = `
                    <label class="text-sm">${ukuran.nama}</label>
                    <input type="number" min="0" value="0" name="ukuran_stok[${ukuran.id}]" class="w-full border p-2 rounded-lg bg-gray-800 dark:bg-orange-900 text-white" required>
                `;
                stokUkuranList.appendChild(row);
            });

            const recalcTotal = () => {
                const total = Array.from(stokUkuranList.querySelectorAll('input[name^="ukuran_stok["]'))
                    .reduce((sum, input) => sum + (parseInt(input.value || '0', 10) || 0), 0);
                document.getElementById('stok-total-value').textContent = total;
            };

            stokUkuranList.querySelectorAll('input[name^="ukuran_stok["]').forEach((input) => {
                input.addEventListener('input', recalcTotal);
            });

            recalcTotal();
        }

        kategoriSelect.addEventListener('change', renderStokByKategori);
        renderStokByKategori();
    </script>
</x-app-layout>