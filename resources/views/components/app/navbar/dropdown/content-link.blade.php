<a
    {{ $attributes->merge([
        'class' => 'flex items-center rounded-md px-3 py-2
                    text-base font-medium text-gray-500 hover:bg-gray-700
                    hover:text-white dark:text-gray-400 dark:hover:bg-gray-500 dark:hover:text-white'
        ])
    }}
>

    {{ $slot }}

</a>
