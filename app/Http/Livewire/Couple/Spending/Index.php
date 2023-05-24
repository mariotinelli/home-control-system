<?php

namespace App\Http\Livewire\Couple\Spending;

use App\Models\CoupleSpending;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;

    public function render(): View
    {
        $this->authorize('viewAny', [CoupleSpending::class]);

        return view('livewire.couple.spending.index');
    }
}
