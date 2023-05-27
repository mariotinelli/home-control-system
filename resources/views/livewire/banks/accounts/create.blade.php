<div>

    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Criar conta bancária</h1>


    {{ $this->form }}

    <button wire:click="save">
        Criar
    </button>

    <button wire:click="saveAndStay">
        Criar e criar novo
    </button>

    <a href="{{ route('banks.accounts.index') }}">
        Cancelar
    </a>

</div>

