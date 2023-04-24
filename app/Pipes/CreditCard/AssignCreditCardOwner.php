<?php

namespace App\Pipes\CreditCard;

use App\Models\CreditCard;
use Closure;

class AssignCreditCardOwner
{
    public function handle(CreditCard $creditCard, Closure $next)
    {
        $creditCard->user_id = auth()->id();

        return $next($creditCard);
    }
}
