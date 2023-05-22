@props([
    'menu',
])

<div
    x-cloak
    x-data="{ open: @js($menu->isExpanded)}"
    @sidebar-collapse.window="open = $event.detail"
>

    <x-app.sidebar.dropdown.menu
        :menu="$menu"
    />

    <x-app.sidebar.dropdown.items
        :menu="$menu"
        :dropdown="$menu->group"
    />

</div>
