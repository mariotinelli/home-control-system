<?php

namespace App\Pipes\Spending;

use App\Models\Spending;
use Closure;


class EmitSpendingCreated
{

    public function __construct(
        private readonly \App\Http\Livewire\CreditCards\Spendings\Create $component
    )
    {
    }

    public function handle(Spending $spending, Closure $next)
    {
        $this->component->emit('credit-card::spending::created');

        return $next($spending);
    }

}
