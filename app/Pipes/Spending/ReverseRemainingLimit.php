<?php

namespace App\Pipes\Spending;

use App\Models\CreditCard;

class ReverseRemainingLimit
{
    public function __construct(
        private readonly float $reverseValue,
        private readonly CreditCard $creditCard
    ) {
    }

    public function handle(mixed $spending, \Closure $next): mixed
    {
        $this->creditCard->remaining_limit = $this->creditCard->remaining_limit + $this->reverseValue;

        return $next($spending);
    }
}
