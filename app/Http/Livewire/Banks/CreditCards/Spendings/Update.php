<?php

namespace App\Http\Livewire\Banks\CreditCards\Spendings;

use App\Models\Spending;
use App\Rules\AmountNotGreaterThanRemainingLimitRule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

    public ?Spending $spending = null;

    public function save(): void
    {
        $this->authorize('update', $this->spending);

        $this->validate();

        $newRemainingLimit = ($this->spending->creditCard->remaining_limit + $this->spending->getOriginal('amount')) - $this->spending->amount;

        $this->spending->creditCard->update([
            'remaining_limit' => $newRemainingLimit,
        ]);

        $this->spending->save();

        $this->emit('credit-card::spending::updated');
    }

    public function render(): View|Factory|Application
    {
        return view('livewire.banks.credit-cards.spendings.update');
    }

    protected function rules(): array
    {
        return [
            'spending.amount' => [
                'required',
                'numeric',
                'max_digits:10',
                new AmountNotGreaterThanRemainingLimitRule($this->spending->creditCard, $this->spending),
            ],
            'spending.description' => ['required', 'string', 'max:255'],
        ];
    }
}
