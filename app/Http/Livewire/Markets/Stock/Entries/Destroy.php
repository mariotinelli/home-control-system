<?php

namespace App\Http\Livewire\Markets\Stock\Entries;

use App\Models\{MarketStock, MarketStockEntry};
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Destroy extends Component
{
    use AuthorizesRequests;

    public ?MarketStock $marketStock = null;

    public ?MarketStockEntry $marketStockEntry = null;

    public function save(): void
    {
        $this->authorize('delete', $this->marketStockEntry);

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
        return view('livewire.markets.stocks.entries.destroy');
    }
}
