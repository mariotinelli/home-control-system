<?php

namespace App\Http\Livewire\Trips\Withdraws;

use App\Models\TripWithdraw;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Destroy extends Component
{
    public ?TripWithdraw $tripWithdraw = null;

    public function save(): void
    {
        $this->tripWithdraw->delete();

        $this->emit('trip::withdraw::deleted');
    }

    public function render(): View
    {
        return view('livewire.trips.withdraws.destroy');
    }
}
