@props([
    'menu',
])

<div
    x-cloak
    x-data="{ isActive: false }"
>

    <x-app.sidebar.menu.item-link
        :menu="$menu"
    />

</div>
