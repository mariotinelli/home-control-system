@props([
    'menu',
    'dropdown',
])

<div
    x-collapse
    role="menu"
    x-show="open"
    class="mt-2 space-y-2"
    aria-label="{{  $menu->name }}"
>

    @foreach($dropdown as $item)

        @if(isset($item->can))

            @can($item->can)

                <x-app.sidebar.dropdown.item-link
                    :item="$item"
                />

            @endcan

        @else

            <x-app.sidebar.dropdown.item-link
                :item="$item"
            />

        @endif

    @endforeach

</div>
