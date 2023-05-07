<?php

namespace App\Http\Livewire\Goals;

use App\Models\Goal;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
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
        $this->authorize('update', $this->goal);

        $this->validate();

        $this->goal->save();

        $this->emit('goal::updated');
    }

    public function render(): View
    {
        return view('livewire.goals.update');
    }
}
