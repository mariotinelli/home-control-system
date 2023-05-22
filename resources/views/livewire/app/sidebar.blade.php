<aside
    class="w-64 min-h-full relative hidden md:block"
    x-data="{ sidebarCollapse : false }"
>

    <div class="flex items-center px-5 bg-gray-800 h-16 space-x-6">

        <x-app.icons.menu-hamburger/>

        <x-app.logo/>

    </div>


    <nav
        aria-label="sidebar"
        class="
            px-3 py-4 overflow-y-auto bg-white border-r dark:border-r-gray-500
            dark:bg-gray-800 h-[calc(100vh-64px)] flex-1 divide-y dark:divide-gray-500
            transition-all duration-200 ease-in-out dark:border-t dark:border-gray-500
        "
        x-bind:class=" sidebarCollapse ? 'w-16' : 'w-64' "
    >

        @foreach($menus as $menu)

            <div class="py-2">

                @if($menu->isGroup)

                    <x-app.sidebar.dropdown
                        :menu="$menu"
                    />

                @else

                    <x-app.sidebar.menu.item
                        :menu="$menu"
                    />

                @endif

            </div>

        @endforeach

    </nav>

</aside>
