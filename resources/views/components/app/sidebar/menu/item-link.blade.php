@props([
    'menu',
])

@php
    $classes = $menu->isActive
        ? 'text-gray-900 bg-blue-200 hover:bg-blue-100
                dark:text-gray-900 dark:bg-white dark:hover:bg-white/50'
        : 'text-gray-600 bg-white hover:bg-gray-200
                dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700';
@endphp

<a
    x-data="{ isActive: @js($menu->isActive) }"
    href=" {{ route($menu->route)  }} "
    class="flex items-center justify-between p-2 text-base font-normal rounded-lg {{ $classes }}"
    role="button"
>

    <div class="flex space-x-3">
        <x-app.icon
            :name="$menu->icon"
            x-bind:class="{ 'text-gray-900': isActive }"
        />

        <span
            class="text-sm font-semibold"
            x-bind:class=" sidebarCollapse ? 'hidden' : '' "
        >
            {{ $menu->name }}
        </span>
    </div>
</a>
