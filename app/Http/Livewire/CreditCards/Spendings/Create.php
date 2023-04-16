<?php

namespace App\Http\Livewire\CreditCards\Spendings;

use App\Models\CreditCard;
use App\Models\Spending;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Pipeline;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public Spending $spending;

    public ?CreditCard $creditCard = null;

    public function getRules(): array
    {
        return [
            'spending.amount' => ['required', 'numeric', 'max_digits:10'],
            'spending.description' => ['required', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', [Spending::class, $this->creditCard]);

        $this->validate();

        Pipeline::send($this->spending)
            ->through([
                (new \App\Pipes\Spending\CalculateRemainingLimit($this->creditCard)),
                (new \App\Pipes\Spending\UpdateCreditCard($this->creditCard)),
                (new \App\Pipes\Spending\AssignCreditCardSpeding($this->creditCard)),
                \App\Pipes\Spending\SaveSpending::class,
                (new \App\Pipes\Spending\EmitSpendingCreated($this)),
            ])
            ->thenReturn();
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
