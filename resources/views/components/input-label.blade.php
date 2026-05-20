@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-white dark:text-gray-100']) }}>
    {{ $value ?? $slot }}
</label>
