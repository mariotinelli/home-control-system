<?php

namespace App\Pipes\Spending;

use App\Models\CreditCard;
use App\Models\Spending;
use Closure;

class UpdateCreditCard
{

    public function __construct(
        private readonly CreditCard $creditCard
    )
    {
    }

    public function handle(Spending $spending, Closure $next)
    {
        $this->creditCard->save();

        return $next($spending);
    }

}
