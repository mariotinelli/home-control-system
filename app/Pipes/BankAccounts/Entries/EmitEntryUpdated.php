<?php

namespace App\Pipes\BankAccounts\Entries;

use App\Http\Livewire\BankAccounts\Entries\Update;
use App\Models\BankAccountEntry;
use Closure;

class EmitEntryUpdated
{
    public function __construct(
        private readonly Update $component
    ) {
    }

    public function handle(BankAccountEntry $entry, Closure $next): BankAccountEntry
    {
        $this->component->emit('bank-account::entry::updated', $entry);

        return $next($entry);
    }
}
