<?php

namespace App\Http\Livewire\Banks\Accounts;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

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
        ;
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.banks.accounts.create');
    }

//    public function save(): void
//    {
//        $this->authorize('create', BankAccount::class);
//
//        $this->validate();
//
//        auth()->user()->bankAccounts()->save($this->bankAccount);
//
//        $this->emit('bank-account::created');
//    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

//    protected function rules(): array
//    {
//        return (new StoreBankAccountRequest())->rules();
//    }
}
