<?php

namespace App\Pipes\BankAccounts\Entries;

use App\Models\BankAccount;
use App\Models\BankAccountEntry;

class UpdateBankAccount
{
    public function __construct(
        private readonly BankAccount $bankAccount
    )
    {
    }

    public function handle(BankAccountEntry $entry, \Closure $next)
    {
        $this->bankAccount->save();

        return $next($entry);
    }
}
