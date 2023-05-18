<?php

namespace App\Http\Livewire\BankAccounts\Entries;

use App\Models\BankAccountEntry;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Pipeline;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

    public ?BankAccountEntry $entry = null;

    public function rules(): array
    {
        return [
            'entry.value'       => ['required', 'decimal:2', 'min:1'],
            'entry.description' => ['required', 'string', 'max:255'],
            'entry.date'        => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->authorize('update', $this->entry);

        $this->validate();

        Pipeline::send($this->entry)
            ->through([
                (new \App\Pipes\BankAccounts\Entries\SaveEntry()),
                (new \App\Pipes\BankAccounts\Entries\ReverseBankAccountBalance($this->entry->bankAccount, $this->entry->getOriginal('value'))),
                (new \App\Pipes\BankAccounts\Entries\CalculateBankAccountBalance($this->entry->bankAccount)),
                (new \App\Pipes\BankAccounts\Entries\UpdateBankAccount($this->entry->bankAccount)),
                (new \App\Pipes\BankAccounts\Entries\EmitEntryUpdated($this)),
            ])
            ->thenReturn();
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.bank-accounts.entries.update');
    }
}
