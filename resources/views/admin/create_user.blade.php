<x-app-layout>
    <div class=" flex justify-center m-5">
        <div class="p-6 max-w-lg bg-gray-600 dark:bg-orange-500 rounded-xl">
            <h1 class="text-xl font-bold mb-4 text-white">Tambah Admin</h1>
        
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="mb-3 text-white">
                    <label>Username</label>
                    <input name="username" placeholder="Masukkan username" class="border p-2 w-full rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
                </div>
        
                <div class="mb-3 text-white">
                    <label>Nama</label>
                    <input name="name" placeholder="Masukkan nama" class="border p-2 w-full rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
                </div>
        
                <div class="mb-3 text-white">
                    <label>Email</label>
                    <input name="email" placeholder="contoh@email.com" class="border p-2 w-full rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
                </div>

                <div class="mb-3 text-white">
                    <label>Nomor HP</label>
                    <input name="nomor_hp" inputmode="numeric" maxlength="13" pattern="08[0-9]{8,11}" placeholder="08xxxxxxxxxx" class="border p-2 w-full rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
                </div>
        
                <div class="mb-3 text-white">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" class="border p-2 w-full rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
                </div>
        
                <div class="flex justify-end">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
    