<?php

namespace App\Http\Livewire\Trips\Entries;

use App\Models\{Trip, TripEntry};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{
    public ?Trip $trip = null;

    public ?TripEntry $tripEntry = null;

    public function getRules(): array
    {
        return [
            'tripEntry.amount'      => ['required', 'numeric', 'min:1'],
            'tripEntry.description' => ['required', 'string', 'max:255'],
            'tripEntry.date'        => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->tripEntry->trip()->associate($this->trip);

        $this->tripEntry->save();

        $this->emit('trip::entry::created');
    }

    public function mount(): void
    {
        $this->tripEntry = new TripEntry();
    }

    public function render(): View
    {
        return view('livewire.trips.entries.create');
    }
}
