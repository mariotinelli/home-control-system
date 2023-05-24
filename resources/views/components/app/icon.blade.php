@props([
    'name',
])

<span aria-hidden="true">
    <x-dynamic-component
        {{ $attributes->merge(['class' => 'w-5 h-5 transition duration-75']) }}
        :component="'heroicon-o-' . $name"
    />
</span>
