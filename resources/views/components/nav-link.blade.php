@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-green-400 dark:border-green-600 text-sm font-medium leading-5
             text-green-800 dark:text-green-400 focus:outline-none focus:border-green-700 transition duration-150 ease-in-out '

            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-black-500 dark:text-gray-100 
            hover:text-green-600 dark:hover:text-green-300 hover:border-green-600 dark:hover:border-green-300 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300
             focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out ';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
