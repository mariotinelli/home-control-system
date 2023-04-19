<?php

namespace App\Rules;

use App\Models\BankAccount;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateBankAccountBalance implements ValidationRule
{
    public function __construct(
        private readonly BankAccount $bankAccount
    )
    {
        //
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->bankAccount->entries()->count() > 0 && $this->bankAccount->withdrawals()->count() > 0) {
            $fail('The balance must be greater than or equal to the sum of the entries and withdrawals.');
        }
    }
}
