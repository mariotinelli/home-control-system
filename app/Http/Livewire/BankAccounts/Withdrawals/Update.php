<?php

namespace App\Http\Livewire\BankAccounts\Withdrawals;

use App\Models\BankAccountWithdraw;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

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
        $this->authorize('update', $this->withdraw);

        $this->validate();

        $this->withdraw->bankAccount->balance += $this->withdraw->getOriginal('value');

        $this->withdraw->bankAccount->balance -= $this->withdraw->value;

        $this->withdraw->bankAccount->save();

        $this->withdraw->save();

        $this->emit('bank-account::withdraw::updated');
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.bank-accounts.withdrawals.update');
    }
}
