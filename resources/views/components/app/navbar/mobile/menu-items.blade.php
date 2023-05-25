<x-app.navbar.mobile.menu-items-link
    @click="$store.darkMode.toggle()"
    role="button"
    x-show="$store.darkMode.on"
    x-cloak
>

    <x-heroicon-o-sun
        class="w-5 h-5 mr-2"
    />

    <span>Modo Claro</span>

</x-app.navbar.mobile.menu-items-link>

<x-app.navbar.mobile.menu-items-link
    @click="$store.darkMode.toggle()"
    role="button"
    x-show="!$store.darkMode.on"
    x-cloak
>

    <x-heroicon-o-moon
        class="w-5 h-5 mr-2"
    />

    <span>Modo Escuro</span>

</x-app.navbar.mobile.menu-items-link>

<form
    method="POST"
    action="{{ route('logout') }}"
>
    @csrf

    <x-app.navbar.mobile.menu-items-link
        :href="route('logout')"
        @click="showProfileDropdown = false"
        onclick="
            event.preventDefault();
            this.closest('form').submit();
        "
    >

        <x-heroicon-o-logout class="w-5 h-5 mr-2"/>

        <span>Logout</span>

    </x-app.navbar.mobile.menu-items-link>

</form>
