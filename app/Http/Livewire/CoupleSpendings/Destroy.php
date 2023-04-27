<?php

namespace App\Http\Livewire\CoupleSpendings;

use App\Models\CoupleSpending;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Destroy extends Component
{
    public ?CoupleSpending $coupleSpending = null;

    public function save(): void
    {

        $this->coupleSpending->delete();

        $this->emit('couple-spending::deleted');

    }

    public function render(): View
    {
        return view('livewire.couple-spendings.destroy');
    }
}
