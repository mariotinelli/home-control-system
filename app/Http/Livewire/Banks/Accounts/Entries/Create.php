<?php

namespace App\Http\Livewire\Banks\Accounts\Entries;

use App\Models\{BankAccount, BankAccountEntry};
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?BankAccount $bankAccount = null;

    public ?BankAccountEntry $entry = null;

    public function rules(): array
    {
        return [
            'entry.value' => ['required', 'numeric', 'min:1'],
            'entry.description' => ['required', 'string', 'max:255'],
            'entry.date' => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', [BankAccountEntry::class, $this->bankAccount]);

        $this->validate();

        $this->bankAccount->entries()->save($this->entry);

        $this->bankAccount->update([
            'balance' => $this->bankAccount->balance + $this->entry->value,
        ]);

        $this->emit('bank-account::entry::created');
    }

    public function mount(): void
    {
        $this->entry = new BankAccountEntry();
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.banks.accounts.entries.create');
    }
}
