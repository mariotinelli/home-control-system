<?php

namespace App\Rules;

use App\Models\{CreditCard, Spending};
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AmountNotGreaterThanRemainingLimitRule implements ValidationRule
{
    public function __construct(
        private readonly CreditCard $creditCard,
        private readonly ?Spending $spending = null
    ) {
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $remainingLimit = $this->spending
            ? $this->creditCard->remaining_limit + $this->spending->getOriginal('amount')
            : $this->creditCard->remaining_limit;

        if ($value > $remainingLimit) {
            $fail('The amount cannot be greater than the remaining limit.');
        }
    }
}
