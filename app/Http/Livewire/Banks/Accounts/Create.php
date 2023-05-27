<?php

namespace App\Http\Livewire\Banks\Accounts;

use App\Actions;
use App\Models\BankAccount;
use Exception;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Livewire\{Component, Redirector};

/**
 * @property ComponentContainer|View|mixed|null $form
 */
class Create extends Component implements HasForms
{
    use AuthorizesRequests;
    use InteractsWithForms;

    public $data;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function render(): View|Factory|Application
    {
        $this->authorize('create', [BankAccount::class]);

        return view('livewire.banks.accounts.create');
    }

    public function save(): Redirector|RedirectResponse
    {
        $this->createBankAccount();

        return to_route('banks.accounts.index');
    }

    public function saveAndStay(): void
    {
        $this->createBankAccount();

        $this->form->fill();
    }

    private function createBankAccount(): void
    {
        $this->authorize('create', [BankAccount::class]);

        $state = $this->form->getState();

        auth()->user()->bankAccounts()->create($state);

        Notification::make()
            ->title('Contas')
            ->body('Conta bancária criada com sucesso!')
            ->success()
            ->send();
    }

    /** @throws Exception */
    protected function getFormSchema(): array
    {
        return Actions\Banks\Accounts\MakeFormSchema::execute();
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }
}
