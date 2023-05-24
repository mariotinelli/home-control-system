@props([
    'item',
])

@php
    $classes = $item->isActive
        ? 'text-gray-900 bg-blue-200 hover:bg-blue-100
                dark:text-gray-900 dark:bg-white dark:hover:bg-white/50'
        : 'text-gray-900 bg-white hover:bg-gray-200
                dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700';
@endphp

<a
    x-data="{ isActive: @js($item->isActive) }"
    href="{{ route($item->route) }}"
    role="menuitem"
    class="block py-2 text-sm px-2 font-semibold transition-colors duration-200 rounded-md {{ $classes }}"
    wire:key="{{ 'dropdown-link-' . $item->name }}"
>

    <div class="flex space-x-2">
        <x-app.icon
            :name="$item->icon"
        />

        <span
            x-show="!sidebarCollapse"
        >
            {{ $item->name }}
        </span>
    </div>

</a>
