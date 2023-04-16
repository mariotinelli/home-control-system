<?php

namespace App\Http\Livewire\CreditCards;

use App\Models\CreditCard;
use App\Pipes\CreditCard\AssignCreditCardOwner;
use App\Pipes\CreditCard\AssignCreditCardRemainingLimit;
use App\Pipes\CreditCard\EmitCreditCardCreated;
use App\Pipes\CreditCard\SaveCreditCard;
use App\Pipes\CreditCards\AssignUser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Pipeline;
use Livewire\Component;

class Create extends Component
{

    public CreditCard $creditCard;

    public function getRules(): array
    {
        return [
            'creditCard.bank' => ['required', 'string', 'max:100', 'min:3'],
            'creditCard.number' => ['required', 'string', 'max:16', 'min:16'],
            'creditCard.expiration' => ['required', 'string', 'max:7', 'min:7'],
            'creditCard.cvv' => ['required', 'numeric', 'max_digits:3', 'min_digits:3'],
            'creditCard.limit' => ['required', 'numeric', 'max_digits:10', 'min_digits:2'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        Pipeline::send($this->creditCard)
            ->through([
                AssignCreditCardOwner::class,
                AssignCreditCardRemainingLimit::class,
                SaveCreditCard::class,
                (new EmitCreditCardCreated($this)),
            ])
            ->then(fn(CreditCard $creditCard) => $creditCard);
    }

    public function mount(): void
    {
        $this->creditCard = new CreditCard();
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.credit-cards.create');
    }
}
