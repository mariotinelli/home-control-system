<?php

namespace App\Pipes\Spending;

use App\Models\CreditCard;
use App\Models\Spending;
use Closure;

class AssignCreditCardSpeding
{

    public function __construct(
        private readonly CreditCard $creditCard
    )
    {
    }

    public function handle(Spending $spending, Closure $next)
    {
        $spending->credit_card_id = $this->creditCard->id;

        return $next($spending);
    }

}