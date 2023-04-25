<?php

namespace App\Pipes\CreditCard;

use App\Models\CreditCard;
use Closure;

class EmitCreditCardCreated
{
    public function __construct(
        private readonly \App\Http\Livewire\CreditCards\Create $component
    ) {
    }

    public function handle(CreditCard $creditCard, Closure $next): CreditCard
    {
        $this->component->emit('credit-card::created');

        return $next($creditCard);
    }
}
