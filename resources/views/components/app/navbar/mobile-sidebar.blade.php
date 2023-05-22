@props([
    'menus'
])

<div
    x-cloak
    class="md:hidden h-[calc(100vh-64px)] overflow-y-auto bg-white dark:border-gray-400 dark:bg-gray-800 "
    x-show="!sidebarCollapse"
    id="mobile-menu"
>

    <div class="border-b border-t dark:border-gray-400 pb-3 pt-5 sticky top-0 z-50">

        <div class="flex items-center px-5 border-b dark:border-gray-400 pb-3">

            <x-app.navbar.mobile.avatar/>

            <x-app.navbar.mobile.user-info/>

            {{--                    <x-app.navbar.mobile.notifications />--}}

        </div>

        <div class="mt-3 space-y-1 px-2 text-gray-600 dark:text-gray-400">

            <x-app.navbar.mobile.menu-items/>

        </div>

    </div>

    <div
        class="px-2 pb-3 pt-2 sm:px-3"
    >
        <nav
            aria-label="sidebar"
            class="
                px-3 py-4 flex-1 divide-y dark:divide-gray-400
                transition-all duration-200 ease-in-out
            "
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
    </div>

</div>
