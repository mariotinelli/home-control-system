<div>

    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Criar conta bancária</h1>


    {{ $this->form }}

    <button wire:click="save">
        Salvar
    </button>

    <button wire:click="saveAndStay">
        Salvar e criar novo
    </button>

</div>

