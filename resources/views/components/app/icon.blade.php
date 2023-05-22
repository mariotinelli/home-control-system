@props([
    'name',
])

<span aria-hidden="true">
    <x-dynamic-component
        {{ $attributes->merge(['class' => 'w-5 h-5 md:text-gray-600 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white']) }}
        :component="'heroicon-o-' . $name"
    />
</span>
