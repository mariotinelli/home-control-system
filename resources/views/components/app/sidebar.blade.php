<aside
    class="w-64 min-h-full relative"
    x-data="{ sidebarCollapse : false }"
>

    <div class="flex items-center px-5 bg-gray-800 h-16 space-x-6">

        <x-app.icons.menu-hamburger/>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-8 w-8"
                         src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
                         alt="Your Company">
                </div>
            </div>
        </div>

    </div>


    <nav
        aria-label="sidebar"
        class="
            px-3 py-4 overflow-y-auto bg-white border-r
            dark:bg-gray-800 h-[calc(100vh-64px)] flex-1 divide-y
            transition-all duration-200 ease-in-out
        "
        x-bind:class=" sidebarCollapse ? 'w-16' : 'w-64' "
    >

        @foreach($menus as $menu)
            <div class="py-2">
                @if(isset($menu->dropdown))

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
