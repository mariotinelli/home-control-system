@props([
    'headerTitle' => null,
    'resourceLabel' => null,
    'actionSave' => null,
])

<div class="flex flex-col space-y-5">

    <div class="flex items-center justify-between mt-6">

        <span class="text-gray-900 font-bold text-2xl"> {{ $headerTitle }} </span>

        <div class="flex space-x-2 items-center">

            <x-forms::button
                title="{{ $resourceLabel }}"
                @click="$dispatch('open-modal', { id: 'resourceModal' })"
                color="success"
            >
                <div class="flex items-center space-x-2">
                    <x-heroicon-o-plus class="w-5 h-5"/>
                    <span> {{ $resourceLabel }} </span>
                </div>
            </x-forms::button>

            @if(isset($headerActions))
                {{ $headerActions }}
            @endif

        </div>

    </div>

    {{ $this->table }}

    {{ $this->createModal }}

    <x-app.filament.resources.modal
        :title="$resourceLabel"
        :action-save="$actionSave"
    />

</div>

