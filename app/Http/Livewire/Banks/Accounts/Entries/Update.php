<?php

namespace App\Http\Livewire\Banks\Accounts\Entries;

use App\Models\BankAccountEntry;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

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
        $this->authorize('update', $this->entry);

        $this->validate();

        $this->entry->bankAccount->update([
            'balance' => ($this->entry->bankAccount->balance - $this->entry->getOriginal('value')) + $this->entry->value,
        ]);

        $this->entry->save();

        $this->emit('bank-account::entry::updated');
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.banks.accounts.entries.update');
    }
}
