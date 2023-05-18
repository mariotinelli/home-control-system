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

    public function handle(BankAccountEntry $entry, Closure $next): BankAccountEntry
    {
        $balance = $this->bankAccount->balance + $entry->value;

        $this->bankAccount->balance = number_format($balance, 2, '.', '');

        return $next($entry);
    }
}
