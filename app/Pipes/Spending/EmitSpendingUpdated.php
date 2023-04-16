<?php

namespace App\Pipes\Spending;

use App\Models\Spending;
use Closure;

class EmitSpendingUpdated
{
    public function __construct(
        private readonly \App\Http\Livewire\CreditCards\Spendings\Update $component
    )
    {
    }

    public function handle(Spending $spending, Closure $next)
    {
        $this->component->emit('credit-card::spending::updated');

        return $next($spending);
    }
}
