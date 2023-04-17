<?php

namespace App\Http\Livewire\BankAccounts\Entries;

use App\Models\BankAccountEntry;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Pipeline;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?BankAccountEntry $entry = null;

    public function save(): void
    {
        $this->authorize('delete', $this->entry);

        Pipeline::send($this->entry)
            ->through([
                (new \App\Pipes\BankAccounts\Entries\ReverseBankAccountBalance($this->entry->bankAccount)),
                (new \App\Pipes\BankAccounts\Entries\UpdateBankAccount($this->entry->bankAccount)),
                (new \App\Pipes\BankAccounts\Entries\DeleteBankAccountEntry()),
                (new \App\Pipes\BankAccounts\Entries\EmitBankAccountEntryDeleted($this)),
            ])
            ->thenReturn();
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.bank-accounts.entries.destroy');
    }
}
