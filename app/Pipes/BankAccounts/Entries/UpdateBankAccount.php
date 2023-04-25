<?php

namespace App\Pipes\BankAccounts\Entries;

use App\Models\{BankAccount, BankAccountEntry};

class UpdateBankAccount
{
    public function __construct(
        private readonly BankAccount $bankAccount
    ) {
    }

    public function handle(BankAccountEntry $entry, \Closure $next): BankAccountEntry
    {
        $this->bankAccount->save();

        return $next($entry);
    }
}
