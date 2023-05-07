<?php

namespace App\Http\Livewire\CoupleSpendings;

use App\Models\CoupleSpending;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?CoupleSpending $coupleSpending = null;

    public function save(): void
    {
        $this->authorize('delete', $this->coupleSpending);

        $this->coupleSpending->delete();

        $this->emit('couple-spending::deleted');

    }

    public function render(): View
    {
        return view('livewire.couple-spendings.destroy');
    }
}
