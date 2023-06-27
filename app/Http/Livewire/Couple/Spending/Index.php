<?php

namespace App\Http\Livewire\Couple\Spending;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function getPlaceWithMoreSpendingProperty(): Model|HasMany
    {
        return $this->user->coupleSpendings()
            ->select([
                'couple_spending_place_id',
                \DB::raw('SUM(amount) as total'),
            ])
            ->groupBy('couple_spending_place_id')
            ->orderBy('total', 'desc')
            ->first();
    }

    public function getCategoryWithMoreSpendingProperty(): Model|HasMany
    {
        return $this->user->coupleSpendings()
            ->select([
                'couple_spending_category_id',
                \DB::raw('SUM(amount) as total'),
            ])
            ->groupBy('couple_spending_category_id')
            ->orderBy('total', 'desc')
            ->first();
    }

}
