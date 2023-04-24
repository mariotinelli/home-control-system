<?php

namespace App\Pipes\Spending;

use App\Models\Spending;
use Closure;

class EmitSpendingDeleted
{
    public function __construct(
        private readonly \App\Http\Livewire\CreditCards\Spendings\Destroy $component
    ) {
    }

    public function handle(Spending $spending, Closure $next)
    {
        $this->component->emit('credit-card::spending::deleted');

        return $next($spending);
    }
}
