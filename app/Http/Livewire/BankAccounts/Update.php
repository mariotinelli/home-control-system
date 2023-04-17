<?php

namespace App\Http\Livewire\BankAccounts;

use App\Models\BankAccount;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

    public ?BankAccount $bankAccount = null;

    public function rules(): array
    {
        return [
            'bankAccount.bank_name' => ['required', 'string', 'max:100'],
            'bankAccount.type' => ['required', 'string', 'max:100'],
            'bankAccount.number' => ['required', 'string', Rule::unique('bank_accounts', 'number')->ignore($this->bankAccount->id, 'id'), 'min:5', 'max:20'],
            'bankAccount.digit' => ['required', 'numeric', 'max_digits:1'],
            'bankAccount.agency_number' => ['required', 'numeric', 'min_digits:4', 'max_digits:4'],
            'bankAccount.agency_digit' => ['nullable', 'numeric', 'max_digits:1'],
            'bankAccount.balance' => ['required', 'numeric', 'max_digits:10'],
        ];
    }

    public function save(): void
    {
        $this->authorize('update', $this->bankAccount);

        $this->validate();

        $this->bankAccount->save();

        $this->emit('bank-account::updated');
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.bank-accounts.update');
    }
}
