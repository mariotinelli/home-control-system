@props([
    'title',
    'actionSave'
])

<x-forms::modal
    id="resourceModal"
>

    <x-slot name="header">
        <h2 class="filament-modal-heading text-xl font-bold tracking-tight">
            {{ $title }}
        </h2>
    </x-slot>

    {{ $this->form }}

    <x-slot name="footer">

        <x-forms::button
            wire:click.prevent="{{ $actionSave }}"
        >
            Salvar
        </x-forms::button>

        <x-forms::button
            @click="$dispatch('close-modal', { id: 'resourceModal' })"
            type="button"
            color="secondary"
        >
            Cancelar
        </x-forms::button>

    </x-slot>

</x-forms::modal>
