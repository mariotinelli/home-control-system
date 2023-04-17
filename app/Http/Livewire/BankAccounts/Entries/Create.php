<?php

namespace App\Http\Livewire\BankAccounts\Entries;

use App\Models\BankAccount;
use App\Models\BankAccountEntry;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Pipeline;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?BankAccount $bankAccount = null;

    public ?BankAccountEntry $entry = null;

    public function rules(): array
    {
        return [
            'entry.value' => ['required', 'numeric', 'min:1', 'max_digits:10'],
            'entry.description' => ['required', 'string', 'max:255'],
            'entry.date' => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', [BankAccountEntry::class, $this->bankAccount]);

        $this->validate();

        Pipeline::send($this->entry)
            ->through([
                (new \App\Pipes\BankAccounts\Entries\AssignBankAccountEntry($this->bankAccount)),
                (new \App\Pipes\BankAccounts\Entries\SaveEntry()),
                (new \App\Pipes\BankAccounts\Entries\CalculateBankAccountBalance($this->bankAccount)),
                (new \App\Pipes\BankAccounts\Entries\UpdateBankAccount($this->bankAccount)),
                (new \App\Pipes\BankAccounts\Entries\EmitEntryCreated($this)),
            ])
            ->thenReturn();
    }

    public function mount(): void
    {
        $this->entry = new BankAccountEntry();
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.bank-accounts.entries.create');
    }
}
