<?php

namespace App\Pipes\BankAccounts\Entries;

use App\Models\BankAccountEntry;

class EmitEntryCreated
{
    public function __construct(
        private readonly \App\Http\Livewire\BankAccounts\Entries\Create $component
    ) {
    }

    public function handle(BankAccountEntry $entry, \Closure $next): BankAccountEntry
    {
        $this->component->emit('bank-account::entry::created');

        return $next($entry);
    }
}
