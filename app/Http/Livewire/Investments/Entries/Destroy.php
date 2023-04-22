<?php

namespace App\Http\Livewire\Investments\Entries;

use App\Models\Investment;
use App\Models\InvestmentEntry;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Destroy extends Component
{

    public ?Investment $investment = null;

    public ?InvestmentEntry $investmentEntry = null;

    public function save(): void
    {
        $this->investmentEntry->delete();

        $this->emit('investment::entry::deleted');
    }

    public function render(): View
    {
        return view('livewire.investments.entries.destroy');
    }
}
