@props([
    'title',
    'actionSave'
])

<x-forms::modal
    id="resourceModal"
    width="4xl"
>

    <x-slot name="header">
        <x-app.filament.resources.modal-heading :title="$title"/>
    </x-slot>

    {{ $this->form }}

    <x-slot name="footer">

        <x-forms::modal.actions>
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
        </x-forms::modal.actions>

    </x-slot>

</x-forms::modal>
