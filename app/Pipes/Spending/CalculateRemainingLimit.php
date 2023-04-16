<?php

namespace App\Pipes\Spending;

use App\Models\CreditCard;
use App\Models\Spending;
use Closure;

class CalculateRemainingLimit
{

    public function __construct(
        private readonly CreditCard $creditCard
    )
    {
    }

    public function handle(Spending $spending, Closure $next)
    {
        $this->creditCard->remaining_limit = $this->creditCard->remaining_limit - $spending->amount;

        return $next($spending);
    }

}
