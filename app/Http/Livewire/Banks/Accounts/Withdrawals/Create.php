<?php

namespace App\Http\Livewire\Banks\Accounts\Withdrawals;

use App\Models\{BankAccount, BankAccountWithdraw};
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
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
            'withdraw.value'       => ['required', 'numeric', 'min:1'],
            'withdraw.description' => ['required', 'string', 'max:255'],
            'withdraw.date'        => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', [BankAccountWithdraw::class, $this->bankAccount]);

        $this->validate();

        $this->bankAccount->withdrawals()->save($this->withdraw);

        $this->bankAccount->update([
            'balance' => $this->bankAccount->balance - $this->withdraw->value,
        ]);

        $this->emit('bank-account::withdraw::created');
    }

    public function mount(): void
    {
        $this->withdraw = new BankAccountWithdraw();
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.banks.accounts.withdrawals.create');
    }
}
