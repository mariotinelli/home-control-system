<?php

namespace App\Http\Livewire\Couple\Spending;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
    public User $user;

    public function mount(): void
    {
        $this->user = auth()->user();
    }

    public function render(): View
    {
        return view('livewire.couple.spending.index');
    }

    public function getBiggestSpendingInMonthProperty(): array
    {
        return $this->user->coupleSpendings()->orderBy('amount', 'desc')->limit(5)->get()->toArray();
    }

}
