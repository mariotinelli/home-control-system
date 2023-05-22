<?php

namespace App\Http\Livewire\Banks\Accounts;

use App\Http\Requests\BankAccount\UpdateBankAccountRequest;
use App\Models\BankAccount;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

    public ?BankAccount $bankAccount = null;

    public function rules(): array
    {
        return (new UpdateBankAccountRequest($this->bankAccount))->rules();
    }

    public function save(): void
    {
        $this->authorize('update', $this->bankAccount);

        $this->validate();

        auth()->user()->bankAccounts()->save($this->bankAccount);

        $this->emit('bank-account::updated');
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.banks.accounts.update');
    }
}
