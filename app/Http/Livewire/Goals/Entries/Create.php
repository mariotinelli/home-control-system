<?php

namespace App\Http\Livewire\Goals\Entries;

use App\Models\{Goal, GoalEntry};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{
    public ?Goal $goal = null;

    public ?GoalEntry $goalEntry = null;

    public function rules(): array
    {
        return [
            'goalEntry.amount' => ['required', 'numeric', 'min:1', 'max:1000'],
            'goalEntry.date'   => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->goalEntry->goal_id = $this->goal->id;

        $this->goalEntry->save();

        $this->emit('goal::entry::created');
    }

    public function mount(): void
    {
        $this->goalEntry = new GoalEntry();
    }

    public function render(): View
    {
        return view('livewire.goals.entries.create');
    }
}
