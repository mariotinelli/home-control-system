<?php

namespace App\Http\Livewire\Markets\Items;

use App\Models\MarketItem;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?MarketItem $marketItem = null;

    public function save(): void
    {
        $this->authorize('delete', $this->marketItem);

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
        return view('livewire.markets.items.destroy');
    }
}
