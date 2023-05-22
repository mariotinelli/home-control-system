@props([
    'menu',
])

<a
    x-data="{ isActive: @js($menu->isActive) }"
    href=" {{ route($menu->route)  }} "
    class="flex items-center justify-between p-2 text-base font-normal md:text-gray-900 rounded-lg dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700"
    x-bind:class="{
        'bg-white text-gray-900 hover:bg-white md:bg-blue-200  md:hover:bg-blue-100': isActive,
        'text-gray-400': ! isActive
    }"
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
