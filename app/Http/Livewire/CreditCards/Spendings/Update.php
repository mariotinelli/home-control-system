<?php

namespace App\Http\Livewire\CreditCards\Spendings;

use App\Models\Spending;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Pipeline;
use Livewire\Component;

class Update extends Component
{

    use AuthorizesRequests;

    public ?Spending $spending = null;

    protected function rules(): array
    {
        return [
            'spending.amount' => ['required', 'numeric', 'max_digits:10'],
            'spending.description' => ['required', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $this->authorize('update', $this->spending);

        $this->validate();

        Pipeline::send($this->spending)
            ->through([
                (new \App\Pipes\Spending\ReverseRemainingLimit($this->spending->getOriginal('amount'), $this->spending->creditCard)),
                (new \App\Pipes\Spending\CalculateRemainingLimit($this->spending->creditCard)),
                (new \App\Pipes\Spending\UpdateCreditCard($this->spending->creditCard)),
                \App\Pipes\Spending\SaveSpending::class,
                (new \App\Pipes\Spending\EmitSpendingUpdated($this)),
            ])
            ->thenReturn();
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.credit-cards.spendings.update');
    }

}
