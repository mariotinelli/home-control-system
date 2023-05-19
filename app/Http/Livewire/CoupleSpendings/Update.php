<?php

namespace App\Http\Livewire\CoupleSpendings;

use App\Models\{CoupleSpending};
use App\Rules\CoupleSpendingCategoryOwnerRule;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

    public ?CoupleSpending $coupleSpending = null;

    public function getRules(): array
    {
        return [
            'coupleSpending.couple_spending_category_id' => [
                'required',
                'exists:couple_spending_categories,id',
                new CoupleSpendingCategoryOwnerRule(),
            ],
            'coupleSpending.description' => ['required', 'string', 'max:255'],
            'coupleSpending.amount'      => ['required', 'numeric', 'min:1'],
            'coupleSpending.date'        => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->authorize('update', $this->coupleSpending);

        $this->validate();

        $this->coupleSpending->save();

        $this->emit('couple-spending::updated');
    }

    public function render(): View
    {
        return view('livewire.couple-spendings.update');
    }
}
