<?php

namespace App\Http\Livewire\CreditCards\Spendings;

use App\Models\{CreditCard, Spending};
use App\Rules\AmountNotGreaterThanRemainingLimitRule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public Spending $spending;

    public ?CreditCard $creditCard = null;

    public function getRules(): array
    {
        return [
            'spending.amount' => ['required',
                'numeric',
                'max_digits:10',
                new AmountNotGreaterThanRemainingLimitRule($this->creditCard),
            ],
            'spending.description' => ['required', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', [Spending::class, $this->creditCard]);

        $this->validate();

        $this->creditCard->update([
            'remaining_limit' => $this->creditCard->remaining_limit - $this->spending->amount,
        ]);

        $this->creditCard->spendings()->save($this->spending);

        $this->emit('credit-card::spending::created');
    }

    public function mount(): void
    {
        $this->spending = new Spending();
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.credit-cards.spendings.create');
    }
}
