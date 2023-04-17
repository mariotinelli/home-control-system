<?php

namespace App\Pipes\BankAccounts\Entries;

use App\Models\BankAccount;
use App\Models\BankAccountEntry;
use Closure;

class AssignBankAccountEntry
{

    public function __construct(
        private readonly BankAccount $bankAccount
    )
    {
    }

    public function handle(BankAccountEntry $entry, Closure $next)
    {
        $entry->bank_account_id = $this->bankAccount->id;

        return $next($entry);
    }
}
