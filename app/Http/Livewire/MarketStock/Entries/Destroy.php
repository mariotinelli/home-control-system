<?php

namespace App\Http\Livewire\MarketStock\Entries;

use App\Models\{MarketStock, MarketStockEntry};
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Destroy extends Component
{
    public ?MarketStock $marketStock = null;

    public ?MarketStockEntry $marketStockEntry = null;

    public function save(): void
    {

        \DB::beginTransaction();

        try {

            $this->marketStock->decrement('quantity', $this->marketStockEntry->quantity);

            $this->marketStockEntry->delete();

            $this->emit('market-stock::entry::deleted');

        } catch (\Exception $e) {

            \DB::rollBack();

        }

    }

    public function render(): View
    {
        return view('livewire.market-stock.entries.destroy');
    }
}
