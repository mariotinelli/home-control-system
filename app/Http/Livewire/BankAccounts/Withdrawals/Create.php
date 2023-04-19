<?php

namespace App\Http\Livewire\BankAccounts\Withdrawals;

use App\Models\BankAccount;
use App\Models\BankAccountWithdraw;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?BankAccount $bankAccount = null;

    public ?BankAccountWithdraw $withdraw = null;

    public function rules(): array
    {
        return [
            'withdraw.value' => ['required', 'numeric', 'min:1', 'max_digits:10'],
            'withdraw.description' => ['required', 'string', 'max:255'],
            'withdraw.date' => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', [BankAccountWithdraw::class, $this->bankAccount]);

        $this->validate();

        $this->withdraw->bank_account_id = $this->bankAccount->id;

        $this->withdraw->save();

        $this->bankAccount->balance -= $this->withdraw->value;

        $this->bankAccount->save();

        $this->emit('bank-account::withdraw::created');
    }

    public function mount(): void
    {
        $this->withdraw = new BankAccountWithdraw();
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.bank-accounts.withdrawals.create');
    }
}
