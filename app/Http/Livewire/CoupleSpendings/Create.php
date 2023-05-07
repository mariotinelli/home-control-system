<?php

namespace App\Http\Livewire\CoupleSpendings;

use App\Models\CoupleSpending;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?CoupleSpending $coupleSpending = null;

    public function getRules(): array
    {
        return [
            'coupleSpending.couple_spending_category_id' => ['required', 'exists:couple_spending_categories,id'],
            'coupleSpending.description'                 => ['required', 'string', 'max:255'],
            'coupleSpending.amount'                      => ['required', 'numeric', 'min:1'],
            'coupleSpending.date'                        => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', CoupleSpending::class);

        $this->validate();

        $this->coupleSpending->save();

        $this->emit('couple-spending::created');
    }

    public function mount(): void
    {
        $this->coupleSpending = new CoupleSpending();
    }

    public function render(): View
    {
        return view('livewire.couple-spendings.create');
    }
}
