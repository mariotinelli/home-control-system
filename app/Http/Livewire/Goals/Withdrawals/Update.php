<?php

namespace App\Http\Livewire\Goals\Withdrawals;

use App\Models\Goal;
use App\Models\GoalWithdraw;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Update extends Component
{

    public ?Goal $goal = null;

    public ?GoalWithdraw $goalWithdraw = null;

    public function rules(): array
    {
        return [
            'goalWithdraw.amount' => ['required', 'numeric', 'min:1', 'max:1000'],
            'goalWithdraw.date' => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->goalWithdraw->save();

        $this->emit('goal::withdraw::updated');
    }

    public function render(): View
    {
        return view('livewire.goals.withdrawals.update');
    }
}
