<?php

namespace App\Pipes\BankAccounts\Entries;

use App\Models\BankAccountEntry;

class DeleteBankAccountEntry
{
    public function __construct()
    {
    }

    public function handle(BankAccountEntry $entry, \Closure $next)
    {
        $entry->delete();

        return $next($entry);
    }
}
