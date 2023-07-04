@props([
    'modalId',
    'width' => '2xl',
    'buttonText',
    'buttonColor' => 'success',
    'buttonIcon' => 'heroicon-o-plus',
    'header',
    'headerTitle',
    'form',
    'footer',
])

<div>

    <x-forms::button
        @click="$dispatch('open-modal', { id: '{{ $modalId }}' })"
        color="{{ $buttonColor }}"
        icon="{{ $buttonIcon }}"
    >
        {{ $buttonText }}
    </x-forms::button>

    <x-forms::modal
        id="{{ $modalId }}"
        width="{{ $width }}"
    >

        @if(isset($header))

            <x-slot name="header">
                {{ $header }}
            </x-slot>

        @else

            <x-slot name="header">
                <h2 class="text-2xl text-gray-800 dark:text-white font-bold"> {{ $headerTitle }} </h2>
            </x-slot>

        @endif

        {{ $form }}

        @if(isset($footer))

            <x-slot name="footer">
                {{ $footer }}
            </x-slot>

        @else

            <x-slot name="footer">

                <div class="flex gap-x-4">

                    <x-forms::button
                        color="success"
                        wire:click="store"
                    >
                        Criar
                    </x-forms::button>

                    <x-forms::button
                        color="secondary"
                        wire:click="storeAndStay"
                    >
                        Criar e criar novo
                    </x-forms::button>

                    <x-forms::button
                        color="secondary"
                        @click="$dispatch('close-modal', { id: '{{ $modalId }}' })"
                    >
                        Cancelar
                    </x-forms::button>

                </div>

            </x-slot>

        @endif

    </x-forms::modal>

    {{ $this->modal }}

</div>
