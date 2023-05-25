@props([
    'menu',
])

@php
    $key = 'sidebar-items' . str($menu->name)->replace(' ', '-')->lower();
@endphp

@if(isset($menu->canGroup))

    @canany($menu->canGroup)

        <div
            class="py-2"
            wire:key="{{ $key }}"
        >

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

        </div>

    @endcan

@else

    <div
        class="py-2"
        wire:key="{{ $key }}"
    >

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

    </div>

@endif
