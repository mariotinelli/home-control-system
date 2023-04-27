<?php

namespace App\Http\Livewire\Trips\Entries;

use App\Models\TripEntry;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Update extends Component
{
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

        $this->tripEntry->save();

        $this->emit('trip::entry::updated');
    }

    public function render(): View
    {
        return view('livewire.trips.entries.update');
    }
}
