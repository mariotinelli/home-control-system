<?php

namespace App\Pipes\BankAccounts;

use App\Models\BankAccount;
use Closure;

class SaveBankAccount
{

    public function handle(BankAccount $bankAccount, Closure $next)
    {
        $bankAccount->save();

        return $next($bankAccount);
    }

}
