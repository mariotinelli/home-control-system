<div
    class="min-h-full z-50"
    x-data="{ sidebarCollapse: true }"
>

    <nav class="bg-gray-800 relative dark:border-b dark:border-gray-500">

        <div class="mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex h-16 items-center justify-between md:justify-end">

                <div class="md:hidden">
                    <x-app.logo/>
                </div>

                <x-app.navbar.desktop-items/>

                <x-app.navbar.mobile-items/>

            </div>
        </div>

        <x-app.navbar.mobile-sidebar
            :menus="$menus"
        />

    </nav>

</div>
