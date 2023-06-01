@props([
    'title',
    'routeIndex'
])

<div class="flex flex-col gap-y-10 max-w-7xl mx-auto mt-5">

    <h1 class="text-2xl font-bold text-gray-900 dark:text-white"> {{ $title }} </h1>

    {{ $this->form }}

    <div class="flex flex-col sm:flex-row items-center gap-4">

        <x-forms::button
            class="w-full sm:w-fit"
            color="success"
            wire:click="store"
        >
            Criar
        </x-forms::button>

        <x-forms::button
            class="w-full sm:w-fit"
            color="secondary"
            wire:click="storeAndStay"
        >
            Criar e criar novo
        </x-forms::button>

        <x-forms::button
            class="w-full sm:w-fit"
            tag="a"
            color="secondary"
            href="{{ route( $routeIndex) }}"
        >
            Cancelar
        </x-forms::button>

    </div>

</div>
