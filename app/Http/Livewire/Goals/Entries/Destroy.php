<?php

namespace App\Http\Livewire\Goals\Entries;

use App\Models\GoalEntry;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?GoalEntry $goalEntry = null;

    public function save(): void
    {

        $this->authorize('delete', $this->goalEntry);

        $this->goalEntry->delete();

        $this->emit('goal::entry::deleted');

    }

    public function render(): View
    {
        return view('livewire.goals.entries.destroy');
    }
}
