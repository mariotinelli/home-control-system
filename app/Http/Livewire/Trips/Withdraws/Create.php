<?php

namespace App\Http\Livewire\Trips\Withdraws;

use App\Models\{Trip, TripWithdraw};
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?Trip $trip = null;

    public ?TripWithdraw $tripWithdraw = null;

    public function getRules(): array
    {
        return [
            'tripWithdraw.amount'      => ['required', 'numeric', 'min:1'],
            'tripWithdraw.description' => ['required', 'string', 'max:255'],
            'tripWithdraw.date'        => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->authorize('create', TripWithdraw::class);

        $this->validate();

        $this->tripWithdraw->trip()->associate($this->trip);

        $this->tripWithdraw->save();

        $this->emit('trip::withdraw::created');
    }

    public function mount(): void
    {
        $this->tripWithdraw = new TripWithdraw();
    }

    public function render(): View
    {
        return view('livewire.trips.withdraws.create');
    }
}
