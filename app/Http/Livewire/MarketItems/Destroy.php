<?php

namespace App\Http\Livewire\MarketItems;

use App\Models\MarketItem;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Destroy extends Component
{
    public ?MarketItem $marketItem = null;

    public function save(): void
    {

        if ($this->marketItem->marketStock()->exists()) {

            $this->addError('marketItem', 'Market item is used in market stock.');

            $this->emit('market-item::delete-failed');

            return;
        }

        $this->marketItem->delete();

        $this->emit('market-item::deleted');
    }

    public function render(): View
    {
        return view('livewire.market-items.destroy');
    }
}
