<?php

namespace App\Pipes\BankAccounts;

use App\Models\BankAccount;
use Closure;

class AssignBankAccountOwner
{
    public function handle(BankAccount $bankAccount, Closure $next)
    {
        $bankAccount->user_id = auth()->id();

        return $next($bankAccount);
    }
}
