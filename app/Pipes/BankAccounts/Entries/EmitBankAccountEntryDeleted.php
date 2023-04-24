<?php

namespace App\Pipes\BankAccounts\Entries;

use App\Models\BankAccountEntry;

class EmitBankAccountEntryDeleted
{
    public function __construct(
        private readonly \App\Http\Livewire\BankAccounts\Entries\Destroy $component
    ) {
    }

    public function handle(BankAccountEntry $entry, \Closure $next)
    {
        $this->component->emit('bank-account::entry::deleted');

        return $next($entry);
    }
}
