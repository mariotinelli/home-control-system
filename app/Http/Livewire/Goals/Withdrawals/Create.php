<?php

namespace App\Http\Livewire\Goals\Withdrawals;

use App\Models\{Goal, GoalWithdraw};
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?Goal $goal = null;

    public ?GoalWithdraw $goalWithdraw = null;

    public function rules(): array
    {
        return [
            'goalWithdraw.amount' => ['required', 'numeric', 'min:1', 'max:1000'],
            'goalWithdraw.date'   => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', GoalWithdraw::class);

        $this->validate();

        $this->goalWithdraw->goal_id = $this->goal->id;

        $this->goalWithdraw->save();

        $this->emit('goal::withdraw::created');
    }

    public function mount(): void
    {
        $this->goalWithdraw = new GoalWithdraw();
    }

    public function render(): View
    {
        return view('livewire.goals.withdrawals.create');
    }
}
