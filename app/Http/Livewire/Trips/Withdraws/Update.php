<?php

namespace App\Http\Livewire\Trips\Withdraws;

use App\Models\TripWithdraw;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

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
        $this->authorize('update', $this->tripWithdraw);

        $this->validate();

        $this->tripWithdraw->save();

        $this->emit('trip::withdraw::updated');
    }

    public function render(): View
    {
        return view('livewire.trips.withdraws.update');
    }
}
