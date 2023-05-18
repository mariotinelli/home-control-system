<?php

namespace App\Pipes\BankAccounts\Entries;

use App\Models\{BankAccount, BankAccountEntry};

class ReverseBankAccountBalance
{
    public function __construct(
        private readonly BankAccount $bankAccount,
        private readonly float       $oldEntryValue
    ) {
    }

    public function handle(BankAccountEntry $entry, \Closure $next): BankAccountEntry
    {
        $balance = $this->bankAccount->balance - $this->oldEntryValue;

        $this->bankAccount->balance = number_format($balance, 2, '.', '');

        return $next($entry);
    }
}
