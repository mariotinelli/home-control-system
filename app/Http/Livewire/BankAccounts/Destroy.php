<?php

namespace App\Http\Livewire\BankAccounts;

use App\Models\BankAccount;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?BankAccount $bankAccount = null;

    public function destroy(): void
    {
        $this->authorize('delete', $this->bankAccount);

        $this->bankAccount->entries()->delete();

        $this->bankAccount->withdrawals()->delete();

        $this->bankAccount->delete();

        $this->emit('bank-account::destroyed');
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.bank-accounts.destroy');
    }
}
