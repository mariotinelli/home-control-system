<?php

namespace App\Http\Livewire\BankAccounts\Entries;

use App\Models\BankAccountEntry;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?BankAccountEntry $entry = null;

    public function save(): void
    {
        $this->authorize('delete', $this->entry);

        $this->entry->bankAccount->update([
            'balance' => $this->entry->bankAccount->balance - $this->entry->value,
        ]);

        $this->entry->delete();

        $this->emit('bank-account::entry::deleted');
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.bank-accounts.entries.destroy');
    }
}
