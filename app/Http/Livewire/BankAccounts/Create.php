<?php

namespace App\Http\Livewire\BankAccounts;

use App\Models\BankAccount;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Pipeline;
use Livewire\Component;

class Create extends Component
{

    public ?BankAccount $bankAccount = null;

    protected function rules(): array
    {
        return [
            'bankAccount.bank_name' => ['required', 'string', 'max:100'],
            'bankAccount.type' => ['required', 'string', 'max:100'],
            'bankAccount.number' => ['required', 'string', 'unique:bank_accounts,number', 'min:5', 'max:20'],
            'bankAccount.digit' => ['required', 'numeric', 'max_digits:1'],
            'bankAccount.agency_number' => ['required', 'numeric', 'min_digits:4', 'max_digits:4'],
            'bankAccount.agency_digit' => ['nullable', 'numeric', 'max_digits:1'],
            'bankAccount.balance' => ['required', 'numeric', 'max_digits:10'],
        ];
    }

    public function save(): void
    {
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
