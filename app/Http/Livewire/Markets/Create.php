<?php

namespace App\Http\Livewire\Markets;

use App\Models\Market;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{

    public function rules(): array
    {
        return [
            'market.name' => ['required', 'string', 'unique:markets,name', 'max:255'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $this->market->save();

        $this->emit('market::created');
    }

    public ?Market $market = null;

    public function mount(): void
    {
        $this->market = new Market();
    }

    public function render(): View
    {
        return view('livewire.markets.create');
    }
}
