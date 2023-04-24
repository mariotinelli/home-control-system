<?php

namespace App\Pipes\BankAccounts\Entries;

use App\Models\{BankAccount, BankAccountEntry};
use Closure;

class CalculateBankAccountBalance
{
    public function __construct(
        private readonly BankAccount $bankAccount
    ) {
    }

    public function handle(BankAccountEntry $entry, Closure $next)
    {
        $this->bankAccount->balance += $entry->value;

        return $next($entry);
    }
}
