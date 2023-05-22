<?php

namespace App\Http\Livewire\Banks\CreditCards;

use App\Models\CreditCard;
use App\Rules\RemainingLimitNotNegativeRule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

    public ?CreditCard $creditCard = null;

    public function getRules(): array
    {
        return [
            'creditCard.bank'       => ['required', 'string', 'max:100', 'min:3'],
            'creditCard.number'     => ['required', 'numeric', 'max_digits:16', 'min_digits:16'],
            'creditCard.expiration' => ['required', 'string', 'max:7', 'min:7'],
            'creditCard.cvv'        => ['required', 'numeric', 'max_digits:3', 'min_digits:3'],
            'creditCard.limit'      => [
                'required',
                'numeric',
                'max_digits:10',
                'min_digits:2',
                new RemainingLimitNotNegativeRule($this->creditCard),
            ],
        ];
    }

    public function save(): void
    {
        $this->authorize('update', $this->creditCard);

        $this->validate();

        $this->creditCard->remaining_limit = $this->creditCard->limit - $this->creditCard->spendings()->sum('amount');

        $this->creditCard->save();

        $this->emit('credit-card::updated');
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.banks.credit-cards.update');
    }
}
