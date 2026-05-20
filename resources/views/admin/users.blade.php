<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between mb-5">
            <h1 class="text-3xl font-bold text-black dark:text-orange-500">Data Pengguna</h1>
            <a href="{{ route('admin.users.create') }}" 
               class="bg-gray-600 hover:bg-gray-800 dark:bg-orange-600 dark:hover:bg-orange-500 text-white px-4 py-2 rounded-lg">
                Tambah Admin
            </a>
        </div>
        <div class="space-y-10">
            <section>
                <h2 class="text-xl font-bold text-black dark:text-orange-400 mb-3">Role 1 - Admin</h2>
                <table class="w-full">
                    <thead class="bg-gray-600 dark:bg-orange-500 text-white">
                        <tr>
                            <th class="p-2">No</th>
                            <th class="p-2">Nama</th>
                            <th class="p-2">Email</th>
                            <th class="p-2">Role</th>
                            <th class="p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adminUsers as $item)
                            <tr class="text-black dark:text-white text-center border-b border-gray-400">
                                <td class="p-2 text-center">
                                    {{ ($adminUsers->currentPage() - 1) * $adminUsers->perPage() + $loop->iteration }}
                                </td>
                                <td class="p-2">{{ $item->name }}</td>
                                <td class="p-2">{{ $item->email }}</td>
                                <td class="p-2">{{ $item->role->name ?? '-' }}</td>
                                <td class="p-2">
                                    <a href="{{ route('admin.users.edit',$item->id) }}"
                                       class="bg-gray-600 hover:bg-gray-800 dark:bg-orange-600 dark:hover:bg-orange-500 text-white px-2 py-1 rounded">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-600 dark:text-gray-300">Belum ada data Role 1.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $adminUsers->links() }}
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold text-black dark:text-orange-400 mb-3">Role 2 - Pelanggan</h2>
                <table class="w-full">
                    <thead class="bg-gray-600 dark:bg-orange-500 text-white">
                        <tr>
                            <th class="p-2">No</th>
                            <th class="p-2">Nama</th>
                            <th class="p-2">Email</th>
                            <th class="p-2">Role</th>
                            <th class="p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customerUsers as $item)
                            <tr class="text-black dark:text-white text-center border-b border-gray-400">
                                <td class="p-2 text-center">
                                    {{ ($customerUsers->currentPage() - 1) * $customerUsers->perPage() + $loop->iteration }}
                                </td>
                                <td class="p-2">{{ $item->name }}</td>
                                <td class="p-2">{{ $item->email }}</td>
                                <td class="p-2">{{ $item->role->name ?? '-' }}</td>
                                <td class="p-2 flex gap-2 justify-center">
                                    <a href="{{ route('admin.users.edit',$item->id) }}"
                                       class="bg-gray-600 hover:bg-gray-800 dark:bg-orange-600 dark:hover:bg-orange-500 text-white px-2 py-1 rounded">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.users.destroy',$item->id) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-600 hover:bg-red-500 text-white px-2 py-1 rounded">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-600 dark:text-gray-300">Belum ada data Role 2.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $customerUsers->links() }}
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
    