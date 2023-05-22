<button>
    <x-app.icon
        x-cloak
        x-show="!sidebarCollapse"
        name="menu"
        class="text-white w-6 h-6 text-white transition-all ease-in-out duration-300"
        role="button"
        @click="
            sidebarCollapse = !sidebarCollapse;
            $nextTick(() => {
                $dispatch('sidebar-collapse', sidebarCollapse)
            })
        "
    />

    <x-app.icon
        x-cloak
        x-show="sidebarCollapse"
        name="x"
        class="text-white w-6 h-6 text-white transition-all ease-in-out duration-300"
        role="button"
        @click="
            sidebarCollapse = !sidebarCollapse;
            $nextTick(() => {
                $dispatch('sidebar-collapse', sidebarCollapse)
            })
        "
    />
</button>
