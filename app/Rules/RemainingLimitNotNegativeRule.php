<?php

namespace App\Rules;

use App\Models\CreditCard;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RemainingLimitNotNegativeRule implements ValidationRule
{
    public function __construct(
        private readonly CreditCard $creditCard,
    ) {

    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (is_numeric($value) && ($value - $this->creditCard->spendings()->sum('amount')) < 0) {
            $fail('The limit is less than the sum of the spendings.');
        }

    }
}
