@props([
    'menu',
])

@php
    $key = 'sidebar-items' . str($menu->name)->replace(' ', '-')->lower();
@endphp

@if(isset($menu->can))

    @can($menu->can)

        <div
            class="py-2"
            wire:key="{{ $key }}"
        >

            <div
                x-cloak
                x-data="{ isActive: false }"
            >

                <x-app.sidebar.menu.item-link
                    :menu="$menu"
                />

            </div>

        </div>

    @endcan

@else

    <div
        class="py-2"
        wire:key="{{ $key }}"
    >

        <div
            x-cloak
            x-data="{ isActive: false }"
        >

            <x-app.sidebar.menu.item-link
                :menu="$menu"
            />

        </div>

    </div>

@endif
