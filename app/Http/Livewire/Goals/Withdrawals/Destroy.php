<?php

namespace App\Http\Livewire\Goals\Withdrawals;

use App\Models\GoalWithdraw;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Destroy extends Component
{
    public ?GoalWithdraw $goalWithdraw = null;

    public function save(): void
    {

        $this->goalWithdraw->delete();

        $this->emit('goal::withdraw::deleted');

    }

    public function render(): View
    {
        return view('livewire.goals.withdrawals.destroy');
    }
}
