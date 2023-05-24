@props([
    'menu',
])

<a
    @click="open = !open"
    class="flex items-center justify-between p-2 text-base font-semibold rounded-lg"
    role="button"
    aria-haspopup="true"
    x-show="!sidebarCollapse"
    :aria-expanded="(open) ? 'true' : 'false'"
    x-bind:class="{
        'text-gray-900 dark:md:text-white md:text-gray-900 dark:text-white': open,
        'text-gray-600 dark:md:text-gray-400 md:text-gray-600 dark:text-gray-400': ! open
    }"
>

    <div class="flex space-x-3">
        <span
            class="text-sm"
            x-bind:class=" sidebarCollapse ? 'hidden' : '' "
        >
            {{ $menu->name }}
        </span>
    </div>

    <x-app.icon
        class="transition-transform transform duration-400 w-3 h-3"
        x-bind:class="{ '-rotate-180' : open }"
        name="chevron-down"
    />
</a>
