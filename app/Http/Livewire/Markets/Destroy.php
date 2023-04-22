<?php

namespace App\Http\Livewire\Markets;

use App\Models\Market;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Destroy extends Component
{
    public ?Market $market = null;

    public function save(): void
    {
        $this->market->delete();

        $this->emit('market::deleted');
    }

    public function render(): View
    {
        return view('livewire.markets.destroy');
    }
}