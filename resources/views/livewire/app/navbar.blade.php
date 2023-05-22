<div
    class="min-h-full z-50"
    x-data="{ sidebarCollapse: true }"
>

    <nav class="bg-gray-800 relative">

        <div class="mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex h-16 items-center justify-between md:justify-end">

                <div class="flex items-center justify-between md:hidden">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-8 w-8"
                                 src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500"
                                 alt="Your Company">
                        </div>
                    </div>
                </div>

                <div class="hidden md:block">

                    <div class="ml-4 flex items-center md:ml-6">
                        <button type="button"
                                class="rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke-width="1.5"
                                 stroke="currentColor"
                                 aria-hidden="true">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                            </svg>
                        </button>

                        <!-- Profile dropdown -->
                        <div class="relative ml-3"
                             x-data="{ showProfileDropdown: false }">
                            <div>
                                <button
                                    type="button"
                                    class="flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                                    id="user-menu-button"
                                    aria-expanded="false"
                                    aria-haspopup="true"
                                    @click="showProfileDropdown = !showProfileDropdown"
                                >
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full"
                                         src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                         alt="">
                                </button>
                            </div>

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

                                    <a
                                        href="#"
                                        class="flex items-center rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-700 hover:text-white"
                                    >
                                        <x-heroicon-o-sun class="w-5 h-5 mr-2"/>
                                        <span>Dark Mode</span>
                                    </a>

                                    <a
                                        href="#"
                                        class="flex items-center rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-700 hover:text-white"
                                    >
                                        <x-heroicon-o-logout class="w-5 h-5 mr-2"/>
                                        <span>Logout</span>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="-mr-2 flex md:hidden">
                    <x-app.icons.menu-hamburger-mobile/>
                </div>

            </div>
        </div>

        {{-- Mobile sidebar --}}
        <div
            x-cloak
            class="md:hidden h-[calc(100vh-64px)] overflow-y-auto"
            x-show="!sidebarCollapse"
            id="mobile-menu"
        >

            <div class="border-b border-t border-gray-700 bg-gray-800 pb-3 pt-5 sticky top-0 z-50">

                <div class="flex items-center px-5">

                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full"
                             src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                             alt="">
                    </div>

                    <div class="ml-3 space-y-1">
                        <div class="text-base font-medium leading-none text-white"> {{ auth()->user()->name }} </div>
                        <div class="text-sm font-medium leading-none text-gray-400"> {{ auth()->user()->email  }} </div>
                    </div>

                    {{--                    <x-app.notifications />--}}

                </div>

                <div class="mt-3 space-y-1 px-2">
                    <a
                        href="#"
                        class="flex items-center rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white"
                    >
                        <x-heroicon-o-logout class="w-5 h-5 mr-2"/>
                        <span>Logout</span>
                    </a>
                </div>

            </div>

            <div
                class="px-2 pb-3 pt-2 sm:px-3"
            >
                <nav
                    aria-label="sidebar"
                    class="
                        px-3 py-4 flex-1 divide-y
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
    </nav>

</div>
