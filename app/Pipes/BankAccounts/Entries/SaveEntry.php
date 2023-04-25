<?php

namespace App\Pipes\BankAccounts\Entries;

use App\Models\BankAccountEntry;
use Closure;

class SaveEntry
{
    public function __construct()
    {
    }

    public function handle(BankAccountEntry $entry, Closure $next): BankAccountEntry
    {
        $entry->save();

        return $next($entry);
    }
}
