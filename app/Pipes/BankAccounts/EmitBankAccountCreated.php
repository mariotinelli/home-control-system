<?php

namespace App\Pipes\BankAccounts;

use App\Models\BankAccount;
use Closure;

class EmitBankAccountCreated
{

    public function __construct(
        private readonly \App\Http\Livewire\BankAccounts\Create $component
    )
    {
    }

    public function handle(BankAccount $bankAccount, Closure $next)
    {
        $this->component->emit('bank-account::created');

        return $next($bankAccount);
    }

}
