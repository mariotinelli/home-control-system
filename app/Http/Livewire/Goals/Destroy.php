<?php

namespace App\Http\Livewire\Goals;

use App\Models\Goal;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?Goal $goal = null;

    public function save(): void
    {
        $this->authorize('delete', $this->goal);

        if ($this->goal->entries()->exists() || $this->goal->withdrawals()->exists()) {

            $this->goal->delete();

            $this->emit('goal::deleted');

            return;

        }

        $this->goal->forceDelete();

        $this->emit('goal::deleted');
    }

    public function render(): View
    {
        return view('livewire.goals.destroy');
    }
}
