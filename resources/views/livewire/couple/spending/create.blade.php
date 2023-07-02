<div>

    <x-app-modal-form
        modal-id="couple-spending-create"
        button-text="Novo gasto"
        header-title="Novo gasto"
        type="create"
    >

        <x-slot name="form">
            {{ $this->form }}
        </x-slot>

    </x-app-modal-form>

</div>
