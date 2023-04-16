<?php

namespace App\Pipes\CreditCard;

use App\Models\CreditCard;
use Closure;

class SaveCreditCard
{

    public function handle(CreditCard $creditCard, Closure $next)
    {
        $creditCard->save();

        return $next($creditCard);
    }

}
