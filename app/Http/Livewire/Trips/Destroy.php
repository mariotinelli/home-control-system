<?php

namespace App\Http\Livewire\Trips;

use App\Models\Trip;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?Trip $trip = null;

    public function save(): void
    {
        $this->authorize('delete', $this->trip);

        if ($this->trip->entries()->count() || $this->trip->withdraws()->count()) {
            $this->addError('trip', 'This trip has entries or withdraws.');

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
