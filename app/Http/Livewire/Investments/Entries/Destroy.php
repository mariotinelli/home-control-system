<?php

namespace App\Http\Livewire\Investments\Entries;

use App\Models\{Investment, InvestmentEntry};
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?Investment $investment = null;

    public ?InvestmentEntry $investmentEntry = null;

    public function save(): void
    {
        $this->authorize('delete', $this->investmentEntry);

        $this->investmentEntry->delete();

        $this->emit('investment::entry::deleted');
    }

    public function render(): View
    {
        return view('livewire.investments.entries.destroy');
    }
}
