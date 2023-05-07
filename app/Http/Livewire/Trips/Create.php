<?php

namespace App\Http\Livewire\Trips;

use App\Models\Trip;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?Trip $trip = null;

    public function rules(): array
    {

        return [
            'trip.city_id'     => ['required', 'integer', 'exists:cities,id'],
            'trip.description' => ['required', 'string', 'max:255'],
            'trip.total_value' => ['required', 'numeric', 'min:1'],
            'trip.month'       => ['required', 'date_format:m/Y'],
        ];

    }

    public function save(): void
    {
        $this->authorize('create', Trip::class);

        $this->validate();

        $this->trip->save();

        $this->emit('trip::created');
    }

    public function mount(): void
    {
        $this->trip = new Trip();
    }

    public function render(): View
    {
        return view('livewire.trips.create');
    }
}
