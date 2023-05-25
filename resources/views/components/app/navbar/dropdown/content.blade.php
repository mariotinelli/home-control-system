<div
    class="absolute right-0 z-50 mt-2 w-fit p-3 divide-y origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
    role="menu"
    aria-orientation="vertical"
    aria-labelledby="user-menu-button"
    tabindex="-1"
    x-cloak
    x-show="showProfileDropdown"
>

    <div class="space-y-2 mb-2 px-4">
        <div class="text-base whitespace-nowrap font-medium leading-none text-gray-500"> {{ auth()->user()->name }} </div>
        <div class="text-sm font-medium leading-none text-gray-400"> {{ auth()->user()->email  }} </div>
    </div>

    <div class="flex flex-col">

        {{-- Dark Mode --}}
        {{--        <x-app.navbar.dropdown.content-link--}}
        {{--            @click="$store.darkMode.toggle(); showProfileDropdown = false"--}}
        {{--            role="button"--}}
        {{--        >--}}

        {{--            <x-heroicon-o-moon--}}
        {{--                x-show="!$store.darkMode.on"--}}
        {{--                x-cloak--}}
        {{--                class="w-5 h-5 mr-2"--}}
        {{--            />--}}

        {{--            <x-heroicon-o-sun--}}
        {{--                x-show="$store.darkMode.on"--}}
        {{--                x-cloak--}}
        {{--                class="w-5 h-5 mr-2"--}}
        {{--            />--}}

        {{--            <span>Modo Escuro</span>--}}

        {{--        </x-app.navbar.dropdown.content-link>--}}

        <x-app.navbar.dropdown.content-link
            @click="$store.darkMode.toggle()"
            role="button"
            x-show="$store.darkMode.on"
            x-cloak
        >

            <x-heroicon-o-sun
                class="w-5 h-5 mr-2"
            />

            <span>Modo Claro</span>

        </x-app.navbar.dropdown.content-link>

        <x-app.navbar.dropdown.content-link
            @click="$store.darkMode.toggle()"
            role="button"
            x-show="!$store.darkMode.on"
            x-cloak
        >

            <x-heroicon-o-moon
                class="w-5 h-5 mr-2"
            />

            <span>Modo Escuro</span>

        </x-app.navbar.dropdown.content-link>

        {{-- Logout --}}
        <form
            method="POST"
            action="{{ route('logout') }}"
        >
            @csrf

            <x-app.navbar.dropdown.content-link
                :href="route('logout')"
                @click="showProfileDropdown = false"
                onclick="
                    event.preventDefault();
                    this.closest('form').submit();
                "
            >
                <x-heroicon-o-logout class="w-5 h-5 mr-2"/>

                <span>Logout</span>

            </x-app.navbar.dropdown.content-link>

        </form>

    </div>

</div>
