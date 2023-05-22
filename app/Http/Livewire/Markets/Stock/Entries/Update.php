<?php

namespace App\Http\Livewire\Markets\Stock\Entries;

use App\Models\{MarketStock, MarketStockEntry};
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

    public ?MarketStock $marketStock = null;

    public ?MarketStockEntry $marketStockEntry = null;

    public function rules(): array
    {
        return [
            'marketStockEntry.market_stock_id' => ['required', 'integer', 'exists:market_stocks,id'],
            'marketStockEntry.market_id'       => ['required', 'integer', 'exists:markets,id'],
            'marketStockEntry.price'           => ['required', 'numeric', 'min:1'],
            'marketStockEntry.quantity'        => ['required', 'integer', 'min:1'],
        ];
    }

    public function save(): void
    {
        $this->authorize('update', $this->marketStockEntry);

        $this->validate();

        \DB::beginTransaction();

        try {

            $this->marketStock->decrement('quantity', $this->marketStockEntry->getOriginal('quantity'));

            if ($this->marketStockEntry->market_stock_id != $this->marketStockEntry->getOriginal('market_stock_id')) {

                $this->marketStock = MarketStock::find($this->marketStockEntry->market_stock_id);

            }

            $this->marketStock->increment('quantity', $this->marketStockEntry->quantity);

            $this->marketStockEntry->save();

            $this->emit('market-stock::entry::updated');

            \DB::commit();

        } catch (\Exception $e) {
            \DB::rollBack();
        }
    }

    public function render(): View
    {
        return view('livewire.markets.stocks.entries.update');
    }
}
