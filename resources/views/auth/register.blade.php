<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full text-black dark:text-white" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" maxlength="100" placeholder="Masukkan nama lengkap" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full text-black dark:text-white" type="email" name="email" :value="old('email')" required autocomplete="email" maxlength="191" placeholder="contoh@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Alamat -->
        <div class="mt-4">
            <x-input-label for="alamat" :value="__('Alamat')" />
            <x-text-input id="alamat" class="block mt-1 w-full text-black dark:text-white" type="text" name="alamat" :value="old('alamat')" required autocomplete="alamat" maxlength="200" placeholder="Masukkan alamat lengkap" />
            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
        </div>

        <!-- Nomor HP -->
        <div class="mt-4">
            <x-input-label for="nomor_hp" :value="__('Nomor Hp')" />
            <x-text-input id="nomor_hp" class="block mt-1 w-full text-black dark:text-white" type="text" name="nomor_hp" :value="old('nomor_hp')" required autofocus autocomplete="nomor_hp" inputmode="numeric" maxlength="15" pattern="08[0-9]{8,11}" placeholder="08xxxxxxxxxx" />
            <x-input-error :messages="$errors->get('nomor_hp')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-password-input id="password" class="block mt-1 text-black dark:text-white"
                            name="password"
                            required autocomplete="new-password" placeholder="Minimal 8 karakter" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-password-input id="password_confirmation" class="block mt-1 text-black dark:text-white"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-100 dark:text-gray-100 hover:text-gray-900 dark:hover:text-gray-400 rounded-md focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-white dark:focus:ring-offset-white" href="{{ route('login') }}">
                {{ __('Sudah Mendaftar?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Daftar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
