<a
    href="#"
    @click="$store.darkMode.toggle()"
    class="flex items-center rounded-md px-3 py-2 text-base font-medium"
>

    <x-heroicon-o-moon
        x-show="!$store.darkMode.on"
        x-cloak
        class="w-5 h-5 mr-2"
    />

    <x-heroicon-o-sun
        x-show="$store.darkMode.on"
        x-cloak
        class="w-5 h-5 mr-2"
    />

    <span>Dark Mode</span>
</a>

<a
    href="#"
    class="flex items-center rounded-md px-3 py-2 text-base font-medium"
>
    <x-heroicon-o-logout class="w-5 h-5 mr-2"/>
    <span>Logout</span>
</a>
