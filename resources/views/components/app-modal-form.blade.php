@props([
    'modalId',
    'width' => '2xl',
    'buttonText',
    'buttonColor' => 'primary',
    'buttonIcon' => 'heroicon-o-plus',
    'header',
    'headerTitle',
    'form',
    'footer',
    'type' => 'create',
])

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
                    wire:click="store"
                >
                    {{ $type == 'create' ? 'Salvar' : 'Atualizar' }}
                </x-forms::button>

                @if($type == 'create')

                    <x-forms::button
                        color="secondary"
                        wire:click="store"
                    >
                        Salvar e continuar
                    </x-forms::button>

                @endif

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
