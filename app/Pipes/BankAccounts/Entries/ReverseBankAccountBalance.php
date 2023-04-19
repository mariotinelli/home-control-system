<?php

namespace App\Pipes\BankAccounts\Entries;

use App\Models\BankAccount;
use App\Models\BankAccountEntry;

class ReverseBankAccountBalance
{

    public function __construct(
        private readonly BankAccount $bankAccount,
        private readonly float       $oldEntryValue
    )
    {
    }

    public function handle(BankAccountEntry $entry, \Closure $next)
    {
        $this->bankAccount->balance -= $this->oldEntryValue;

        return $next($entry);
    }
}
