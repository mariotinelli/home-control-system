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

        {{-- Dark --}}
        <a
            href="#"
            @click="$store.darkMode.toggle(); showProfileDropdown = false"
            class="flex items-center rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-700 hover:text-white"
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
            @click="showProfileDropdown = false"
            class="flex items-center rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-700 hover:text-white"
        >
            <x-heroicon-o-logout class="w-5 h-5 mr-2"/>
            <span>Logout</span>
        </a>
    </div>

</div>
