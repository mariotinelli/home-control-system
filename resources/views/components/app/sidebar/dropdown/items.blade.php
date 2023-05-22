@props([
    'menu',
    'dropdown',
])

<div
    x-collapse
    role="menu"
    x-show="open"
    class="mt-2 space-y-2 px-2"
    aria-label="{{  $menu->name }}"
>

    @foreach($dropdown as $item)

        <x-app.sidebar.dropdown.item-link
            :item="$item"
        />

    @endforeach

</div>
