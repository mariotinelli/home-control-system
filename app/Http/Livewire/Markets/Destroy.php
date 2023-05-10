<?php

namespace App\Http\Livewire\Markets;

use App\Models\Market;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?Market $market = null;

    public function save(): void
    {
        $this->authorize('delete', $this->market);

        if ($this->market->marketStockEntries()->exists()) {

            $this->addError('market', 'Market contains entries.');

            $this->emit('market::delete-failed');

            return;
        }

        $this->market->delete();

        $this->emit('market::deleted');
    }

    public function render(): View
    {
        return view('livewire.markets.destroy');
    }
}
