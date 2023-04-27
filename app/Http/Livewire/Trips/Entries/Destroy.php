<?php

namespace App\Http\Livewire\Trips\Entries;

use App\Models\TripEntry;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Destroy extends Component
{
    public ?TripEntry $tripEntry = null;

    public function save(): void
    {
        $this->tripEntry->delete();

        $this->emit('trip::entry::deleted');
    }

    public function render(): View
    {
        return view('livewire.trips.entries.destroy');
    }
}
