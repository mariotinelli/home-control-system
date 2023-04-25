<?php

namespace App\Pipes\BankAccounts\Entries;

use App\Models\{BankAccount, BankAccountEntry};
use Closure;

class AssignBankAccountEntry
{
    public function __construct(
        private readonly BankAccount $bankAccount
    ) {
    }

    public function handle(BankAccountEntry $entry, Closure $next): BankAccountEntry
    {
        $entry->bank_account_id = $this->bankAccount->id;

        return $next($entry);
    }
}
