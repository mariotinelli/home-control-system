<?php

namespace App\Http\Livewire\Goals;

use App\Models\Goal;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?Goal $goal = null;

    public function rules(): array
    {
        return [
            'goal.name'        => ['required', 'string', 'max:255'],
            'goal.to_reach'    => ['required', 'numeric', 'min:1'],
            'goal.owner'       => ['required', 'string', 'max:255'],
            'goal.description' => ['required', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', Goal::class);

        $this->validate();

        $this->goal->save();

        $this->emit('goal::created');
    }

    public function mount(): void
    {
        $this->goal = new Goal();
    }

    public function render(): View
    {
        return view('livewire.goals.create');
    }
}
