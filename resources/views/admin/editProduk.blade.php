<x-app-layout>
    <div class="p-6 max-w-xl mx-auto text-white bg-gray-600 dark:bg-[#E67E22] m-5 rounded-xl">
        <h1 class="text-2xl font-bold mb-4">Edit Produk</h1>
    
        <form action="{{ route('admin.produk.update',$produk->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
    
            <div class="mb-3">
                <label class="text-white">Nama Produk</label>
                <input type="text" name="nama" value="{{ $produk->nama }}" placeholder="Masukkan nama produk" class="w-full border p-2 rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
            </div>
    
            <div class="mb-3">
                <label class="text-white">Harga</label>
                <div class="flex items-center">
                    <span class="bg-gray-700 p-2 rounded-l-lg text-white border-y border-l">Rp</span>
                    <input type="text" id="harga" name="harga" inputmode="numeric" value="{{ number_format((int) $produk->harga, 0, ',', '.') }}" placeholder="Contoh: 150.000" class="w-full border p-2 rounded-r-lg bg-gray-800 dark:bg-orange-900 text-white">
                </div>
            </div>

            <div class="mb-3">
                <label class="text-white">Kategori</label>
                <select id="kategori_id" name="kategori_id" class="w-full border p-2 -lg bg-gray-800 dark:bg-orange-900 text-white">
                    @foreach($kategori as $k)
                        <option value="{{ $k->id }}" 
                            {{ $produk->kategori_id == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label class="text-white">Merek</label>
                <select name="merek_id" class="w-full border p-2 rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
                    @foreach($merek as $m)
                        <option value="{{ $m->id }}" 
                            {{ $produk->merek_id == $m->id ? 'selected' : '' }}>
                            {{ $m->nama }}
                        </option>
                    @endforeach
                </select>
            </div>            
    
            <div class="mb-3" id="stok-umum-wrapper">
                <label class="text-white">Stok</label>
                <input type="number" id="stok_umum" name="stok" value="{{ $produk->stok }}" placeholder="Masukkan jumlah stok" class="w-full border p-2 rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
            </div>

            <div class="mb-3 hidden" id="stok-ukuran-wrapper">
                <label class="text-white">Stok per Ukuran</label>
                <div id="stok-ukuran-list" class="grid grid-cols-2 gap-3 mt-2"></div>
                <p class="mt-3 font-semibold text-white">Total Stok: <span id="stok-total-value">0</span></p>
            </div>
    
            <div class="mb-3">
                <label class="text-white">Deskripsi</label>
                <textarea name="deskripsi" placeholder="Masukkan deskripsi produk" class="w-full border p-2 rounded-lg bg-gray-800 dark:bg-orange-900 text-white">{{ $produk->deskripsi }}</textarea>
            </div>
    
            <div class=" grid grid-cols-2">
                @php
                $fields = ['gambarproduk','gambarproduk1','gambarproduk2','gambarproduk3'];
                @endphp

                @foreach($fields as $field)
                <div class="mb-3">
                    <label class="text-white">{{ ucfirst($field) }}</label>
                    <input type="file" name="{{ $field }}" class="w-full">

                    @if($produk->$field)
                        <img src="{{ asset('img/produk/'.$produk->$field) }}" class="w-24 mt-2">
                    @endif
                </div>
                @endforeach
            </div>

    
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.produk.index') }}"
                   class="bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded-lg">
                    Kembali
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-800 text-white px-4 py-2 rounded-lg">Update</button>
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

        $existingUkuranStokMap = $produk->ukuranStoks->pluck('stok', 'ukuran_id');
    @endphp

    <script id="kategori-ukuran-json" type="application/json">@json($kategoriUkuranMap)</script>
    <script id="existing-ukuran-stok-json" type="application/json">@json($existingUkuranStokMap)</script>

    <script>
    const hargaInput = document.getElementById('harga');
    const kategoriSelect = document.getElementById('kategori_id');
    const stokUmumWrapper = document.getElementById('stok-umum-wrapper');
    const stokUmumInput = document.getElementById('stok_umum');
    const stokUkuranWrapper = document.getElementById('stok-ukuran-wrapper');
    const stokUkuranList = document.getElementById('stok-ukuran-list');

    const kategoriUkurans = JSON.parse(document.getElementById('kategori-ukuran-json').textContent || '{}');

    const existingUkuranStok = JSON.parse(document.getElementById('existing-ukuran-stok-json').textContent || '{}');

    function formatRupiah(value) {
        const angka = value.replace(/\D/g, '');

        if (!angka) {
            return '';
        }

        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    hargaInput.addEventListener('input', function () {
        this.value = formatRupiah(this.value);
    });

    // Pastikan nilai awal dari database langsung tampil dalam format rupiah.
    hargaInput.value = formatRupiah(hargaInput.value);

    function renderStokByKategori() {
        const ukuranList = kategoriUkurans[kategoriSelect.value] || [];
        const hasUkuran = ukuranList.length > 0;

        stokUmumWrapper.classList.toggle('hidden', hasUkuran);
        stokUkuranWrapper.classList.toggle('hidden', !hasUkuran);
        stokUmumInput.required = !hasUkuran;

        stokUkuranList.innerHTML = '';

        if (!hasUkuran) {
            document.getElementById('stok-total-value').textContent = stokUmumInput.value || '0';
            return;
        }

        ukuranList.forEach((ukuran) => {
            const currentStok = existingUkuranStok[ukuran.id] ?? 0;
            const row = document.createElement('div');
            row.innerHTML = `
                <label class="text-sm text-white">${ukuran.nama}</label>
                <input type="number" min="0" value="${currentStok}" name="ukuran_stok[${ukuran.id}]" class="w-full border p-2 rounded-lg bg-gray-800 dark:bg-orange-900 text-white" required>
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