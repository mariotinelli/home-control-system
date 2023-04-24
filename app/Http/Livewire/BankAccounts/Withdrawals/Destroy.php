<?php

namespace App\Http\Livewire\BankAccounts\Withdrawals;

use App\Models\BankAccountWithdraw;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?BankAccountWithdraw $withdraw = null;

    public function save(): void
    {
        $this->authorize('delete', $this->withdraw);

        $this->withdraw->bankAccount->balance += $this->withdraw->value;

        $this->withdraw->bankAccount->save();

        $this->withdraw->delete();

        $this->emit('bank-account::withdraw::deleted');
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.bank-accounts.withdrawals.destroy');
    }
}
