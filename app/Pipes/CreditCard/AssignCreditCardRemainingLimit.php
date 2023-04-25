<?php

namespace App\Pipes\CreditCard;

use App\Models\CreditCard;
use Closure;

class AssignCreditCardRemainingLimit
{
    public function handle(CreditCard $creditCard, Closure $next): CreditCard
    {
        $creditCard->remaining_limit = $creditCard->limit;

        return $next($creditCard);
    }
}
