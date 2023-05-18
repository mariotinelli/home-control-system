<?php

namespace App\Http\Livewire\BankAccounts;

use App\Http\Requests\BankAccount\StoreBankAccountRequest;
use App\Models\BankAccount;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Pipeline;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?BankAccount $bankAccount = null;

    protected function rules(): array
    {
        return (new StoreBankAccountRequest())->rules();
    }

    public function save(): void
    {
        $this->authorize('create', BankAccount::class);

        $this->validate();

        Pipeline::send($this->bankAccount)
            ->through([
                \App\Pipes\BankAccounts\AssignBankAccountOwner::class,
                \App\Pipes\BankAccounts\SaveBankAccount::class,
                (new \App\Pipes\BankAccounts\EmitBankAccountCreated($this)),
            ])
            ->thenReturn();
    }

    public function mount(): void
    {
        $this->bankAccount = new BankAccount();
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.bank-accounts.create');
    }
}
