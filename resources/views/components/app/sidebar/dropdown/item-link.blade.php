@props([
    'item',
])

<a
    x-data="{ isActive: @js($item->isActive) }"
    href="{{ route($item->route) }}"
    role="menuitem"
    class="block py-2 text-sm font-semibold transition-colors duration-200 rounded-md"
    x-bind:class="{
        'px-2': ! sidebarCollapse,
        'bg-white text-gray-900 md:bg-blue-200 hover:bg-blue-100': isActive,
        'hover:bg-gray-200 dark:hover:bg-gray-700': ! isActive,
    }"

>

    <div class="flex space-x-2">
        <x-app.icon
            :name="$item->icon"
            x-bind:class="{ 'text-gray-400 md:text-gray-900': ! isActive }"
        />

        <span
            x-show="!sidebarCollapse"
            x-bind:class="{ 'text-gray-400 md:text-gray-900': ! isActive }"
        >
            {{ $item->name }}
        </span>
    </div>

</a>
