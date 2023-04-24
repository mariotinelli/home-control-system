<?php

namespace App\Http\Livewire\Goals\Entries;

use App\Models\{Goal, GoalEntry};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Update extends Component
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

        $this->goalEntry->save();

        $this->emit('goal::entry::updated');

    }

    public function render(): View
    {
        return view('livewire.goals.entries.update');
    }
}
