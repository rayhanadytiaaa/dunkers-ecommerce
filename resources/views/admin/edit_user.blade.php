<x-app-layout>
    <div class=" flex justify-center m-5">
        <div class="p-6 max-w-lg bg-gray-600 dark:bg-orange-500 rounded-xl text-white">
            <h1 class="text-xl font-bold mb-4">Edit User</h1>
        
            <form method="POST" action="{{ route('admin.users.update',$user->id) }}">
                @csrf
                @method('PUT')
        
                <div class="mb-3">
                    <label>Nama</label>
                    <input name="name" value="{{ $user->name }}" placeholder="Masukkan nama" class="border p-2 w-full rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
                </div>

                <div class="mb-3">
                    <label>Username</label>
                    <input name="username" value="{{ $user->username }}" placeholder="Masukkan username" class="border p-2 w-full rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
                </div>
        
                <div class="mb-3">
                    <label>Email</label>
                    <input name="email" value="{{ $user->email }}" placeholder="contoh@email.com" class="border p-2 w-full rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
                </div>

                <div class="mb-3" id="alamat-wrapper" @if($user->role_id == 1) style="display: none;" @endif>
                    <label>Alamat</label>
                    <input id="alamat" name="alamat" value="{{ $user->alamat }}" placeholder="Masukkan alamat lengkap" class="border p-2 w-full rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
                </div>

                <div class="mb-3">
                    <label>Nomor Hp</label>
                    <input name="nomor_hp" value="{{ $user->nomor_hp }}" inputmode="numeric" maxlength="13" pattern="08[0-9]{8,11}" placeholder="08xxxxxxxxxx" class="border p-2 w-full rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
                </div>
        
                <div class="mb-3">
                    <label>Role</label>
                    <div class="border p-2 w-full rounded-lg bg-gray-800 dark:bg-orange-900 text-white">
                        {{ $user->role_id == 1 ? 'Admin' : 'Pelanggan' }}
                    </div>
                    <input type="hidden" name="role_id" value="{{ $user->role_id }}">
                </div>
        
                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.users') }}" class="bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded">
                        Kembali
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
    