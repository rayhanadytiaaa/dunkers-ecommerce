<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts min-h-screen bg-[#1A1A1A] -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class=" min-h-screen bg-[#F8F9FA] dark:bg-[#1A1A1A]">
            @include('layouts.navigation')

            <!-- Page Heading bg-[#F4F4F4] -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}

                @if(session('success'))
                <div 
                    id="checkout-toast"
                    class="fixed top-20 right-6 z-50 bg-green-600 text-white px-6 py-4 rounded-xl shadow-lg
                        animate-toastIn">
                    <div class="flex items-center gap-2">
                        <span></span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
                @elseif(session('error'))
                <div 
                    id="checkout-toast"
                    class="fixed top-20 right-6 z-50 bg-red-600 text-white px-6 py-4 rounded-xl shadow-lg
                        animate-toastIn">
                    <div class="flex items-center gap-2">
                        <span></span>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
                @endif

            </main>

            <x-site-footer />
        </div>
    </body>

    
    <script>
        setTimeout(() => {
            const toast = document.getElementById('checkout-toast');
            if (toast) {
                toast.classList.remove('animate-toastIn');
                toast.classList.add('animate-toastOut');

                setTimeout(() => toast.remove(), 400);
            }
        }, 3000);
    </script>
</html>
