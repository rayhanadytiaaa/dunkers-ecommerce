@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-gray-100 dark:border-gray-100 text-sm font-medium leading-5 text-gray-100 dark:text-gray-100 focus:outline-none focus:border-gray-100 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-100 dark:text-gray-100 hover:text-gray-100 dark:hover:text-gray-500 hover:border-gray-500 dark:hover:border-gray-500 focus:outline-none focus:text-gray-500 dark:focus:text-gray-500 focus:border-gray-500 dark:focus:border-gray-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
