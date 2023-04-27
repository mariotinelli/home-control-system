<?php

namespace App\Http\Livewire\Trips;

use App\Models\Trip;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Destroy extends Component
{
    public ?Trip $trip = null;

    public function save(): void
    {
        if ($this->trip->entries()->count()) {
            $this->addError('trip', 'This trip has entries.');

            return;
        }

        $this->trip->delete();

        $this->emit('trip::deleted');

    }

    public function render(): View
    {
        return view('livewire.trips.destroy');
    }
}
