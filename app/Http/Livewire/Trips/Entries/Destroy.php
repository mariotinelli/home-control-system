<?php

namespace App\Http\Livewire\Trips\Entries;

use App\Models\TripEntry;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?TripEntry $tripEntry = null;

    public function save(): void
    {
        $this->authorize('delete', $this->tripEntry);

        $this->tripEntry->delete();

        $this->emit('trip::entry::deleted');
    }

    public function render(): View
    {
        return view('livewire.trips.entries.destroy');
    }
}
